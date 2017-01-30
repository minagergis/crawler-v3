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

    public $acceptedurl;


    /*Contructor Run Here To Start the Class */


    public function __construct(Client $client)

    {

        $this->client = $client;

    }


    public function getIndex()

    {

        //$this->url = 'http://tbsjournal.arabmediasociety.com/Archives/Fall05/index05.html';

        $this->url = 'http://tbsjournal.arabmediasociety.com/Archives/Spring05/SpringSummer2005.html';

        //$this->base_url='http://tbsjournal.arabmediasociety.com/Archives/Fall05/';

        $this->base_url='http://tbsjournal.arabmediasociety.com/Archives/Spring05/';

        $this->setScrapUrl($this->url);

        $this->filters = [

            'title' => 'p > font',

            'image' => 'table > tr > td > img',

            'image-cap' => 'table > tr > td > font',

            'desc' => 'p > font'

        ];

        dd($this->getContents());


        return view('scraper')->with('content', $this->getContents());

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


    private function startScraper()
    {


        $countContent = $this->clawler->filter('body > table > tr')->count();
        $result = array();

        if ($countContent) {
            for ($x = 1; $x < $countContent; $x++) {
                $this->content[] = $this->clawler->filter('body > table > tr')->eq($x)->each(function (Crawler $node, $i) {
                    $bold = $node->filter('a')->count();
                    var_dump($bold);
                    for ($o = 0; $o < $bold; $o++) {
                        $articleurl = $node->filter('a')->eq($o)->attr('href');
                        $articletitle=$node->filter('a')->eq($o)->text();
                        if (strpos($articleurl, 'mailto') === false && strpos($articleurl, 'http') === false && strpos($articleurl, 'Drag%20to%20a%20file') === false && strpos($articleurl, 'ArabAdvisors') === false) {

                            $articleurl=$this->base_url.$articleurl;
                            $this->CarwelInnerPage($articleurl);
                            $page_content=$this->innerClawler->filter('body > div > table > tr > td > div')->eq(2)->html();
                            echo "<h1>".$articletitle."</h1>";
                            var_dump($page_content);
                            Article::insert(['title' => $articletitle, 'desc' => $page_content,'url'=>$articleurl , 'issue_id'=>2 ]);
                            echo "<br /><br /><hr />";
                           
                        }
                    }
                    



                });
            }
            die();
        }

        return $this->content;


    }

    private function CarwelInnerPage($url = NULL, $method = 'GET')
    {
        $this->innerClawler = $this->client->request($method, $url);
    }


}
