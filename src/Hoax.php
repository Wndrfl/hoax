<?php namespace Wndrfl\Hoax;

class Hoax {

	const LOREM = 'lorem ipsum dolor sit amet consectetuer adipiscing elit aenean commodo ligula eget dolor aenean massa cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus donec quam felis ultricies nec pellentesque eu pretium quis sem nulla consequat massa quis enim donec pede justo fringilla vel aliquet nec vulputate eget arcu in enim justo rhoncus ut imperdiet a venenatis vitae justo nullam dictum felis eu pede mollis pretium integer tincidunt cras dapibus vivamus elementum semper nisi aenean vulputate eleifend tellus aenean leo ligula porttitor eu consequat vitae eleifend ac enim aliquam lorem ante dapibus in viverra quis feugiat a tellus phasellus viverra nulla ut metus varius laoreet quisque rutrum aenean imperdiet etiam ultricies nisi vel augue curabitur ullamcorper ultricies nisi nam eget dui etiam rhoncus maecenas tempus tellus eget condimentum rhoncus sem quam semper libero sit amet adipiscing sem neque sed ipsum nam quam nunc blandit vel luctus pulvinar hendrerit id lorem maecenas nec odio et ante tincidunt tempus donec vitae sapien ut libero venenatis faucibus nullam quis ante etiam sit amet orci eget eros faucibus tincidunt duis leo sed fringilla mauris sit amet nibh donec sodales sagittis magna sed consequat leo eget bibendum sodales augue velit cursus nunc quis gravida magna mi a libero fusce vulputate eleifend sapien vestibulum purus quam scelerisque ut mollis sed nonummy id metus nullam accumsan lorem in dui cras ultricies mi eu turpis hendrerit fringilla vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; in ac dui quis mi consectetuer lacinia nam pretium turpis et arcu duis arcu tortor suscipit eget imperdiet nec imperdiet iaculis ipsum sed aliquam ultrices mauris integer ante arcu accumsan a consectetuer eget posuere ut mauris praesent adipiscing phasellus ullamcorper ipsum rutrum nunc nunc nonummy metus vestibulum volutpat pretium libero cras id dui aenean ut eros et nisl sagittis vestibulum nullam nulla eros ultricies sit amet nonummy id imperdiet feugiat pede sed lectus donec mollis hendrerit risus phasellus nec sem in justo pellentesque facilisis etiam imperdiet imperdiet orci nunc nec neque phasellus leo dolor tempus non auctor et hendrerit quis nisi curabitur ligula sapien tincidunt non euismod vitae posuere imperdiet leo maecenas malesuada praesent congue erat at massa sed cursus turpis vitae tortor donec posuere vulputate arcu phasellus accumsan cursus velit vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; sed aliquam nisi quis porttitor congue elit erat euismod orci ac';
	
	/**
	 * Generate random coordinates around center
	 * @param  double  $latitude  The latitude
	 * @param  double  $longitude The longitude
	 * @param  integer $range_min Min range in feet
	 * @param  integer $range_max Max range in feet
	 * @return array             Array of coordinates
	 */
	static function coordinatesAround($latitude,$longitude,$range_min=1,$range_max=52800)
	{
		$latitude = (float) $latitude;
		$longitude = (float) $longitude;
		$radius = rand($range_min,$range_max); // in in feet
		$radius = $radius/5280; // convert to miles

		$lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
		$lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
		$lat_min = $latitude - ($radius / 69);
		$lat_max = $latitude + ($radius / 69);

		return array(
			rand($lat_min*100000000,$lat_max*100000000)/100000000,
			rand($lng_min*100000000,$lng_max*100000000)/100000000
		);
	}

	/**
	 * Generate a random date between two other dates
	 * @param  string|date $earliest The earliest possible
	 * @param  string|date $latest   The latest possible date
	 * @param  string $format   The date formate to return
	 * @return string           A random string date
	 */
	static function date($earliest=null,$latest=null,$format='Y-m-d G:i:s') {
		$earliest = ($earliest) ? strtotime($earliest) : strtotime('-900 days');
		$latest = ($latest) ? strtotime($latest) : time();
		
		return date('Y-m-d G:i:s',mt_rand($earliest, $latest));
	}

	/**
	 * Generate random domain
	 * @return string A domain
	 */
	static function domain()
	{
		$domains = explode(' ',self::LOREM);
		$domain = $domains[array_rand($domains)];

		$protos = ['','http://','https://'];
		$proto = $protos[array_rand($protos)];

		$subdomains = ['www'];
		$subdomain = $subdomains[array_rand($subdomains)];

		$tlds = ['com','net','biz','org','io','co'];
		$tld = $tlds[array_rand($tlds)];

		return $proto.$subdomain.'.'.$domain.'.'.$tld;
	}

	/**
	 * Generate a random title
	 * @param  int $min Minimum words
	 * @param  int $max Maximum words
	 * @return string      A random title
	 */
	static function title($min,$max=null)
	{
		$length = ($max) ? rand($min,$max) : $min;

		$parts = explode(' ',self::LOREM);
		shuffle($parts);
		$parts_len = count($parts);

		$i = 0;
		$words = [];

		for($i=0;$i<$length;$i++) {
			$word = $parts[rand(0,$parts_len-1)];
			$words[] = $word;
		}

		return ucwords(implode(' ',$words));
	}

	/**
	 * Generate a random price
	 * @param  int $min Minimum price
	 * @param  int $max Maximum price
	 * @return float      A random price
	 */
	static function price($min,$max)
	{
		return number_format(mt_rand ($min*10, $max*10) / 10,2,'.','');
	}

	/**
	 * Use randomuser.me to generate a random user
	 * @param  int $total The number of users to create
	 * @return array        An array of users
	 */
	static function users($total)
	{
		$total = 100;
		$url="http://api.randomuser.me/?results=".$total;
		$result = file_get_contents($url);
		$random_users = json_decode($result, true);

		$users = [];
		foreach($random_users["results"] as $random_user) {
			$users[] = $random_user["user"];
		}

		return $users;
	}

	/**
	 * Generate random words in the form of sentences.
	 * @param  int  $min         The minimum amount of words
	 * @param  int  $max         The maximum amount of words
	 * @param  boolean $punctuation Whether to use punctuation
	 * @return string               Random words in sentence format
	 */
	static function sentences($min,$max=null,$punctuation=true)
	{
		$length = ($max) ? rand($min,$max) : $min;

		$parts = explode(' ',self::LOREM);
		shuffle($parts);
		$parts_len = count($parts);

		$i = 0;
		$comma_spaces = array(3,9,20);
		$min_per_sentence = 5;
		$max_per_sentence = 20;
		$sentence = $sentences = [];
		$sentence_len = rand($min_per_sentence,$max_per_sentence);

		for($i=0;$i<$length;$i++) {
			$word = $parts[rand(0,$parts_len-1)];
			if($punctuation && in_array($sentence_len,$comma_spaces) && rand(0,1)) {
				$word .= ',';
			}

			$sentence[] = $word;

			if(count($sentence) >= $sentence_len) {
				shuffle($parts);
				$sentences[] = $sentence;
				$sentence = [];
				$sentence_len = rand($min_per_sentence,$max_per_sentence);
			}
		}

		if($sentence) {
			$sentences[] = $sentence;
		}

		$str = [];
		foreach($sentences as $sentence) {
			$str[] = ucfirst(implode(' ',$sentence)).(($punctuation) ? '.' : '');
		}

		return implode(' ',$str);
	}
}