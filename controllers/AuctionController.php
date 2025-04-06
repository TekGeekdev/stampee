<?php
namespace App\Controllers;

use App\Models\Auction;
use App\Models\Color;
use App\Models\Conditions;
use App\Models\CountryOrigin;
use App\Models\FileToUpload;
use App\Models\Stamp;
use App\Models\User;
use App\Providers\View;

class AuctionController
{

    public function home()
    {
        return View::render('auction/home');
    }

    public function index()
    {
        $objAuction = new Auction;
        $auctions   = $objAuction->select();

        $objStamp        = new Stamp;
        $objFileToUpload = new FileToUpload;
        $obtCondition    = new Conditions;

        foreach ($auctions as $auctionsIndex => $auction) {
            $stampInfo                               = $objStamp->selectId($auction['stamp_id']);
            $auctions[$auctionsIndex]['nameStamp']   = $stampInfo['name'];
            $auctions[$auctionsIndex]['dateRelease'] = $stampInfo['dateRelease'];

            $conditionInfo                         = $obtCondition->selectId($stampInfo['conditions_id']);
            $auctions[$auctionsIndex]['condition'] = $conditionInfo["state"];

            $stampImages = $objFileToUpload->selectAllById($auction['stamp_id'], "stamp_id");

            foreach ($stampImages as $stampImagesIndex => $image) {
                if ($image["position"] == 0) {
                    $auctions[$auctionsIndex]['fileName']        = $image['file'];
                    $auctions[$auctionsIndex]['fileDescription'] = $image['description'];
                }
            }
        }
        // echo('<pre>');
        // print_r($auctions);
        // echo('</pre>');
        return View::render('auction/catalogue', ['auctions' => $auctions]);
    }

    public function show()
    {
        $get     = ! empty($get) ? $get : $_GET;
        $idAuction = $get["id"];

        $objAuction = new Auction;
        $objStamp        = new Stamp;
        $objFileToUpload = new FileToUpload;
        $objCondition    = new Conditions;
        $objCountry      = new CountryOrigin;
        $objColor        = new Color;
        $objUser         = new User;

        $auction = $objAuction->selectId($idAuction);
        $stampInfo = $objStamp->selectId($auction["stamp_id"]);

        $colorName              = $objColor->selectId($stampInfo["color_id"]);
        $stampInfo["colorName"] = $colorName["color"];

        $userName              = $objUser->selectId($stampInfo["user_id"]);
        $stampInfo["userName"] = $userName["name"];

        $countryName              = $objCountry->selectId($stampInfo["countryOrigin_id"]);
        $stampInfo["countryName"] = $countryName["country"];

        $conditionState              = $objCondition->selectId($stampInfo["conditions_id"]);
        $stampInfo["conditionState"] = $conditionState["state"];

        $images = $objFileToUpload->selectAllById($stampInfo["id"], "stamp_id");

        echo('<pre>');
        print_r($images);
        print_r($auction);
        print_r($stampInfo);
        echo('</pre>');

        return View::render('auction/show');
    }
}
