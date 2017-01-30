<?php


namespace App\Http\Controllers;


use App\Http\Requests;
use App\Article;
use Faker\Provider\Base;
use Goutte\Client;
use Illuminate\Routing\Controller as BaseController;
use Session;
use Symfony\Component\DomCrawler\Crawler;


class WebCrawlingController extends BaseController

{


    /* if i have a dependency injection later i will insert it here  **- Minaa -**  */


    public $url;
    public $base_url;
    public $clawler;
    public $innerClawler;
    public $filters;
    public $content = array();
    private $client;


    /*Contructor Run Here To Start the Class */


    public function __construct(Client $client)

    {

        $this->client = $client;

    }


    public function getIndex()

    {


        $this->url = 'http://tbsjournal.arabmediasociety.com/Archives/Fall03/fall03.htm';

        $this->base_url='http://tbsjournal.arabmediasociety.com/Archives/Fall03/';

        $this->setScrapUrl($this->url);

        dd($this->getContents());

    }


    public function setScrapUrl($url = NULL, $method = 'GET')

    {

        $this->clawler = $this->client->request($method, $url);

        return $this->clawler;

    }


    public function getContents()
    {

        return $this->content = $this->startScraper();

    }

     private function CarwelInnerPage($url = NULL, $method = 'GET')
    {
        $this->innerClawler = $this->client->request($method, $url);
    }

    private function startScraper()
    {

        $count=$this->clawler->filter('table > tr > td')->count();

           $this->clawler = $this->clawler->filter('table > tr > td')->eq(7);
           $cat_title = $this->clawler->filter('table > tr')->eq(0)->text();
           
           echo $cat_title;
           $mina=$this->clawler->filter('table')->eq(5);
           $mina=$mina->filter('tr')->eq(0)->html();
           dd($mina);
        
           
        die();



        return $this->content;


    }

   

}