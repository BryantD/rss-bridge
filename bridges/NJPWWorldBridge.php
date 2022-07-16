<?php

class NJPWWorldBridge extends BridgeAbstract {
	const NAME = 'njpwworld.com';
	const URI = 'https://front.njpwworld.com';
	const DESCRIPTION = 'Fetches new video releases from NJPW';
	const MAINTAINER = 'bryantd';
        const PARAMETERS = array(
                array(
                        'lang' => array(
                                'name' => 'Language',
                                'type' => 'list',
                                'values' => array(
                                        'English' => 'en',
                                        'Japanese' => 'ja'
                                ),
                                'required' => true,
                                'title' => 'Language to use',
                                'defaultValue' => 'en'
                        ),
			'wrestler' => array(
				'name' => 'wrestler',
				'type' => 'list', 
                                'values' => array(
					'All' => false,
					'Minoru Suzuki' 	=> 'man_282_en',
                                        'Taichi' 		=> 'man_380_en',
                                        'Zack Sabre Jr.'	=> 'man_546_en',
					'El Desperado'		=> 'man_483_en',
					'Yoshinobu Kanemaru'	=> 'man_677_en',
					'DOUKI'			=> 'man_777_en',
                                ),
                                'required' => false,
                                'title' => 'Wrestler search'
			)
                )
        );
	const CACHE_TIMEOUT = 21600; // 6 hours

	public function collectData() {
                $lang = $this->getInput('lang');
		$wrestler_tag = $this->getInput('wrestler');
		if ($wrestler_tag) {
			$uri = self::URI . '/search/tag/' . $wrestler_tag;
		}
		else {
			$uri = self::URI . '/search/latest';
		}
		$uri .= '?lang=' . $lang . '#googtrans(' . $lang . ')';
		$html = getSimpleHTMLDOM($uri)
			or returnServerError('Could not request: ' . $url);

		foreach ($html->find('div.movieArea') as $element) {
                        $videoURL = self::URI . $element->find('a', 0)->getAttribute('href');
                        $title = trim(str_replace("　", "", $element->plaintext));

                        $thumbnail = $element->find('img', 0)->getAttribute('src');
                        if ($element->find('dt[class=i-free]')) {
                            $type = 'Free';
                        }
                        else {
                            $type = 'Video';
                        }
                        $content = '<p><img src="' . self::URI . $thumbnail . '"/></p>';
			$content .= '<p>' . $type . ': ' . $title . '</p>';

                        $item = array();
                        $item['uri'] = $videoURL;
                        $item['uid'] = $videoURL;
                        $item['title'] = $title;
                        $item['content'] = $content;
                        //$item['enclosures']  = array(self::URI . $thumbnail);
                        $this->items[] = $item;
		}
	}
}
