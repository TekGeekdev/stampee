<?php
namespace App\Controllers;

use App\Models\Auction;
use App\Models\Bid;
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
        $objBid          = new Bid;
        $objUser         = new User;

        foreach ($auctions as $auctionsIndex => $auction) {
            $stampInfo                               = $objStamp->selectId($auction['stamp_id']);
            $auctions[$auctionsIndex]['nameStamp']   = $stampInfo['name'];
            $auctions[$auctionsIndex]['dateRelease'] = $stampInfo['dateRelease'];

            $conditionInfo                         = $obtCondition->selectId($stampInfo['conditions_id']);
            $auctions[$auctionsIndex]['condition'] = $conditionInfo["state"];

            $maxBid = $objBid->selectWhereIdMax("auction_id", $auction["id"], "bid");
            if ($maxBid && ! empty($maxBid)) {
                $auctions[$auctionsIndex]['maxBid']        = $maxBid["bid"];
                $selectBidderName                          = $objUser->selectIdWhere("id", $maxBid["user_id"]);
                $auctions[$auctionsIndex]['maxBidderName'] = $selectBidderName["name"];
            } else {
                $auctions[$auctionsIndex]['maxBid']        = "Aucune mise";
                $auctions[$auctionsIndex]['maxBidderName'] = "Aucun(e)";
            }

            $stampImages = $objFileToUpload->selectAllById($auction['stamp_id'], "stamp_id");

            foreach ($stampImages as $stampImagesIndex => $image) {
                if ($image["position"] == 0) {
                    $auctions[$auctionsIndex]['fileName']        = $image['file'];
                    $auctions[$auctionsIndex]['fileDescription'] = $image['description'];
                }
            }
        }
        return View::render('auction/catalogue', ['auctions' => $auctions]);
    }

    public function show()
    {
        $get       = ! empty($get) ? $get : $_GET;
        $idAuction = $get["id"];

        $objAuction      = new Auction;
        $objStamp        = new Stamp;
        $objFileToUpload = new FileToUpload;
        $objCondition    = new Conditions;
        $objCountry      = new CountryOrigin;
        $objColor        = new Color;
        $objUser         = new User;
        $objBid          = new Bid;

        $auction = $objAuction->selectId($idAuction);

        $maxBid = $objBid->selectWhereIdMax("auction_id", $auction["id"], "bid");
        if ($maxBid && ! empty($maxBid)) {
            $auction['maxBid']        = $maxBid["bid"];
            $selectBidderName         = $objUser->selectIdWhere("id", $maxBid["user_id"]);
            $auction['maxBidderName'] = $selectBidderName["name"];
        } else {
            $auction['maxBid']        = "Aucune mise";
            $auction['maxBidderName'] = "Aucun(e)";
        }

        $stampInfo = $objStamp->selectId($auction["stamp_id"]);

        $colorName              = $objColor->selectId($stampInfo["color_id"]);
        $stampInfo["colorName"] = $colorName["color"];

        $userName              = $objUser->selectId($stampInfo["user_id"]);
        $stampInfo["userName"] = $userName["name"];

        $countryName              = $objCountry->selectId($stampInfo["countryOrigin_id"]);
        $stampInfo["countryName"] = $countryName["country"];

        $conditionState              = $objCondition->selectId($stampInfo["conditions_id"]);
        $stampInfo["conditionState"] = $conditionState["state"];

        $stampImages = $objFileToUpload->selectAllById($stampInfo["id"], "stamp_id", "position");

        return View::render('auction/show', ["images" => $stampImages, "auction" => $auction, "stampInfo" => $stampInfo]);
    }
}
