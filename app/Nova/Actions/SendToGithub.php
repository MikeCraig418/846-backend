<?php

namespace App\Nova\Actions;

use Github\Exception\RuntimeException;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SendToGithub extends Action {
	use InteractsWithQueue, Queueable;

	/**
	 * Perform the action on the given models.
	 *
	 * @param  \Laravel\Nova\Fields\ActionFields  $fields
	 * @param  \Illuminate\Support\Collection  $models
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $models) {
		// validate that all LinkSubmissions have required fields in a separate loop
		// so that we do not get partial submissions
		foreach ($models as $model) {
			$submission = $model->data;
			if ($this->isFieldInvalid($submission, 'title')) {
				return Action::danger("You must give the submission a title");
			}
			if ($this->isFieldInvalid($submission, 'description')) {
				return Action::danger("You must give the submission a description");

			}
			if ($this->isFieldInvalid($submission, 'links')) {
				return Action::danger("You must give the submission links");

			}
			if ($this->isFieldInvalid($submission, 'tags')) {
				return Action::danger("You must give the submission tags");

			}
			if ($this->isFieldInvalid($submission, 'state')) {
				return Action::danger("You must give the submission a state");

			}
			// ok a state was entered, but is it a valid state
			if ($this->isFieldInvalid($this->stateMapping, $submission['state'])) {
				return Action::danger("The provided state must be a valid US state abbreviation");

			}
			if ($this->isFieldInvalid($submission, 'city')) {
				return Action::danger("You must give the submission a city");

			}
			if ($this->isFieldInvalid($submission, 'date')) {
				return Action::danger("You must give the submission a date.  Include 'believed to be' if unsure");
			}
		}
		foreach ($models as $model) {
			$submission = $model->data;
			$this->Send($submission['city'], $submission['state'], $submission['description'], $submission['tags'], $submission['links'], $submission['title'], $submission['date']);
		}
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields() {
		return [];
	}

	private function isFieldInvalid(array $data, string $field): bool {
		return !array_key_exists($field, $data) || is_null($data[$field]) || $data[$field] == '';
	}

	public function Send(string $city, string $state, string $description, string $tags, string $links_str, string $title, string $date) {
		//Retrieve the name input field
		$links = preg_split("/,/", $links_str);

		$repo_owner = config('846.link_submission_github_repo_owner');
		$repo_name = config('846.link_submission_github_repo_name');
		$username = config('846.github_username');
		$branch = config('846.link_submission_github_branch');
		dump(config('846'));
		dump($repo_owner);
		dump($username);
		// TODO: reflect actual Laravel user
		$commit_message = 'Approved from Laravel by user YYYY';

		$md_file_path = $this->makeStateFileName($state);

		$content = '';
		$sha = null;
		$is_new_state = false;

		try {
			$git_resp = GitHub::repo()->Contents()->Show($repo_owner, $repo_name, $md_file_path, $branch);
			$encoded_content = $git_resp['content'];
			$content = base64_decode($encoded_content);
			$sha = $git_resp['sha'];
		} catch (RuntimeException $e) {
			if ($e->GetCode() != 404) {
				throw $e;
			}
			// If you are creating a state's first recorded incident
			$is_new_state = true;
		}

		if (!$is_new_state) {
			$updated_content = $this->addNewIncident($content, $city, $state, $title, $date, $description, $tags, $links);
			GitHub::repo()->Contents()->update($username, $repo_name, $md_file_path, $updated_content, $commit_message, $sha, $branch);
		} else {
			$new_content = $this->addNewStateIncident($city, $state, $title, $date, $description, $tags, $links);
			GitHub::repo()->Contents()->create($username, $repo_name, $md_file_path, $new_content, $commit_message, $branch);
		}

	}

	private function makeStateFileName(string $city_abbreviation): string{
		$full_state_name = $this->stateMapping[$city_abbreviation];
		return 'reports/' . $full_state_name . '.md';
	}

	private function addNewIncident(string $existing_state_content, string $city, string $state_abbrev, string $title, string $date, string $description, string $tags, array $links): string{

		$clean_city = trim(strtolower($city));

		// Parse existing md file
		$city_md_block_pattern = '/^## +(?:(?!^## ).)*/ms';
		// populates $cities_matches
		preg_match_all($city_md_block_pattern, $existing_state_content, $cities_matches);
		$cities = $cities_matches[0];

		$incidents_blob = '';
		$city_name_pattern = '/^##\s+([^#]+)$/m';
		$request_city_index = 0;
		$new_city_index = 0;
		$is_new_city = true;

		foreach ($cities as $i => $city_in_md) {
			preg_match($city_name_pattern, $city_in_md, $city_name_matches);
			$found_city_name = trim(strtolower($city_name_matches[1]));
			if (strcmp($clean_city, $found_city_name) == 0) {
				$incidents_blob = $city_in_md;
				$request_city_index = $i;
				$is_new_city = false;
				break;
			}
			$sorted_city_pair = [$clean_city, $found_city_name];
			sort($sorted_city_pair);
			if ($new_city_index == 0 && $sorted_city_pair[1] == $clean_city) {
				$new_city_index = $i + 1;
			}
		}

		// Insert new incident into md file contents
		if ($is_new_city) {
			$id_incident = $this->buildIDIncident($state_abbrev, $clean_city, 1);
			$new_incident = view('new-city-incident-template', ['city' => $city, 'title' => $title, 'date' => $date, 'description' => $description, 'tags' => $tags, 'links' => $links, 'id' => $id_incident]);
			array_splice($cities, $new_city_index, 0, strval($new_incident));
			return implode($cities);
		}

		$current_max_id = $this->getMaxIDIncident($incidents_blob);
		$id_incident = $this->buildIDIncident($state_abbrev, $clean_city, $current_max_id + 1);
		$new_incident = view('incident-template', ['title' => $title, 'date' => $date, 'description' => $description, 'tags' => $tags, 'links' => $links, 'id' => $id_incident]);

		$new_incidents_blob = $incidents_blob . strval($new_incident);
		// overwrite existing entry for this city
		$cities[$request_city_index] = $new_incidents_blob;
		return implode($cities);
	}

	private function addNewStateIncident(string $city, string $state_abbrev, string $title, string $date, string $description, string $tags, array $links): string{

		$clean_city = trim(strtolower($city));
		$id_incident = $this->buildIDIncident($state_abbrev, $clean_city, 1);
		$new_content = view('new-city-incident-template', ['city' => $city, 'title' => $title, 'date' => $date, 'description' => $description, 'tags' => $tags, 'links' => $links, 'id' => $id_incident]);
		return strval($new_content);
	}

	private function buildIDIncident(string $state_abbrev, string $city, int $id_number): string{
		$city_section = str_replace(' ', '', $city);
		return strtolower($state_abbrev . '-' . $city_section . '-' . strval($id_number));
	}

	// cannot just use the size of the incidents array, because sometimes incidents are merged together
	// making it so using the count would cause duplicate ids
	private function getMaxIDIncident(string $incidents): int{
		$max_id = 0;
		preg_match_all('/^id: (.*)$/m', $incidents, $id_match);
		foreach ($id_match[0] as $id) {
			$id_num = intval(preg_split('/-/', $id)[2]);
			if ($id_num > $max_id) {
				$max_id = $id_num;
			}
		}
		return $max_id;
	}

	private $stateMapping = [
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'Washington DC',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	];

}
