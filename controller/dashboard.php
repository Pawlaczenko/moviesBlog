<?php

class dashboardController extends Controller {
    
    public function __construct()
    {
        $data['siteTitle'] = "Co ci uczniowie wyrabiają";
        $data['siteHeading'] = "same problemy...";
        $data['siteMeta'] = "Publikacja - Marcin Natanek, od 10 października 2019";

        $this->render("dashboard", $data);
    }
}