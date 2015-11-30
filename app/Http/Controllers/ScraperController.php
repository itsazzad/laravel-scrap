<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Goutte\Client;

class ScraperController extends Controller
{
    

    public function getIndex()
    {

    	$client = new Client();
    	//Set proxy using tor
		$guzzleClient = new \GuzzleHttp\Client([
		    'curl' => [
		        CURLOPT_PROXY => '127.0.0.1:9050',
		        CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5,
		    ],
		]);  

		$client->setClient($guzzleClient);	

		$crawler = $client->request('GET', 'http://auburn.craigslist.org/apa');

		$isBlock = $crawler->filter('p')->text();
		
		if(strpos($isBlock,'blocked') != false ) {

			echo "Get a New Ip";
			echo exec('sudo service tor restart');
		} else {

			$crawler->filter('p.row')->each(function ($node) {
				    print $node->attr("data-pid")."\n";
				    echo $node->filter('.hdrlnk')->text();
				});
		}


    }

    /**
     * @using gutte proxy 
     */

    public function getGutte()
    {

    	$client = new Client();
    	//Set proxy using tor
		$guzzleClient = new \GuzzleHttp\Client([
		    'curl' => [
		        CURLOPT_PROXY => '127.0.0.1:9050',
		        CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5,
		    ],
		]);  

		$client->setClient($guzzleClient);	

		$crawler = $client->request('GET', 'http://188.166.243.11');

		dd($crawler->html());

    }

    public function getGit()
    {	$client = new Client();
		$crawler = $client->request('GET', 'http://github.com/');
		$crawler = $client->click($crawler->selectLink('Sign in')->link());
		$form = $crawler->selectButton('Sign in')->form();
		$crawler = $client->submit($form, array('login' => 'sohel4r@gmail.com', 'password' => '$Carbon123'));

		$crawler->filter('.header-logo')->each(function ($node) {
		    print $node->text()."\n";
		});    	
    }
}
