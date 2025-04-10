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
use App\Providers\Date;
use App\Providers\Validator;
use App\Providers\View;

class BidController
{
    public function store($data = [])
    {

        $validator = new Validator;
        $validator->field('bid', $data['bid'], "Le champ enchère")->number();

        if (! empty($_SESSION)) {
            $objAuction      = new Auction;
            $objStamp        = new Stamp;
            $objFileToUpload = new FileToUpload;
            $obtCondition    = new Conditions;
            $objBid          = new Bid;
            $objUser         = new User;
            $objDate         = new Date;

            if ($validator->isSuccess()) {

                $auctionJustBid = $objAuction->selectId($data["id"]);

                $maxBid = $objBid->selectWhereIdMax("auction_id", $data["id"], "bid");

                if ($data["bid"] > $auctionJustBid["startPrice"] && $data["bid"] > $maxBid["bid"]) {
                    $dataInsert               = [];
                    $dataInsert["bid"]        = $data["bid"];
                    $dataInsert["auction_id"] = $data["id"];
                    $dataInsert["user_id"]    = $_SESSION['user_id'];

                    $insertBid = $objBid->insert($dataInsert);
                    if ($insertBid) {
                        return view::redirect('auction');
                    } else {
                        return View::render('error', ['msg' => "Impossible de saisir l'enchère"]);
                    }

                } else {
                    return View::render('error', ['msg' => "Votre enchère est trop petite."]);
                }

            } else {

                $auctions = $objAuction->select();

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

                    $dateNow                              = $objDate->dateNow();
                    $timeLeft                             = $objDate->dateDiff($dateNow, $auction["dateFinish"]);
                    $auctions[$auctionsIndex]['timeLeft'] = $timeLeft;

                    $stampImages = $objFileToUpload->selectAllById($auction['stamp_id'], "stamp_id");

                    foreach ($stampImages as $stampImagesIndex => $image) {
                        if ($image["position"] == 0) {
                            $auctions[$auctionsIndex]['fileName']        = $image['file'];
                            $auctions[$auctionsIndex]['fileDescription'] = $image['description'];
                        }
                    }
                }

                $errors = $validator->getErrors();
                return View::render('auction/catalogue', ['errors' => $errors, 'bidData' => $data, 'auctions' => $auctions]);
            }

        } else {
            return view::redirect('login');
        }
    }

    public function storeShow($data = [])
    {

        $validator = new Validator;
        $validator->field('bid', $data['bid'], "Le champ enchère")->number();

        if (! empty($_SESSION)) {
            $objAuction      = new Auction;
            $objStamp        = new Stamp;
            $objFileToUpload = new FileToUpload;
            $objCondition    = new Conditions;
            $objCountry      = new CountryOrigin;
            $objColor        = new Color;
            $objUser         = new User;
            $objBid          = new Bid;
            $objDate         = new Date;

            $get       = ! empty($get) ? $get : $_GET;
            $idAuction = $get["id"];

            if ($validator->isSuccess()) {

                $auctionJustBid = $objAuction->selectId($data["id"]);

                $maxBid = $objBid->selectWhereIdMax("auction_id", $data["id"], "bid");

                if ($data["bid"] > $auctionJustBid["startPrice"] && $data["bid"] > $maxBid["bid"]) {
                    $dataInsert               = [];
                    $dataInsert["bid"]        = $data["bid"];
                    $dataInsert["auction_id"] = $data["id"];
                    $dataInsert["user_id"]    = $_SESSION['user_id'];

                    $insertBid = $objBid->insert($dataInsert);
                    if ($insertBid) {
                        return view::redirect('auction');
                    } else {
                        return View::render('error', ['msg' => "Impossible de saisir l'enchère"]);
                    }

                } else {
                    return View::render('error', ['msg' => "Votre enchère est trop petite."]);
                }

            } else {
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

        $dateNowAuction                              = $objDate->dateNow();
        $timeLeftAuction                         = $objDate->dateDiff($dateNowAuction, $auction["dateFinish"]);
        $auction['timeLeft'] = $timeLeftAuction;

        $stampImageInfo = $objFileToUpload->selectAllById($stampInfo["id"], "stamp_id", "position");

        $bannerAuctions = $objAuction->selectByLimit("dateStart", 4, "DESC");
        foreach ($bannerAuctions as $auctionsIndex => $bannerAuction) {
            $stampInfoBanner                               = $objStamp->selectId($bannerAuction['stamp_id']);
            $bannerAuctions[$auctionsIndex]['nameStamp']   = $stampInfoBanner['name'];
            $bannerAuctions[$auctionsIndex]['dateRelease'] = $stampInfoBanner['dateRelease'];

            $conditionInfo                               = $objCondition->selectId($stampInfoBanner['conditions_id']);
            $bannerAuctions[$auctionsIndex]['condition'] = $conditionInfo["state"];

            $maxBid = $objBid->selectWhereIdMax("auction_id", $bannerAuction["id"], "bid");
            if ($maxBid && ! empty($maxBid)) {
                $bannerAuctions[$auctionsIndex]['maxBid']        = $maxBid["bid"];
                $selectBidderName                                = $objUser->selectIdWhere("id", $maxBid["user_id"]);
                $bannerAuctions[$auctionsIndex]['maxBidderName'] = $selectBidderName["name"];
            } else {
                $bannerAuctions[$auctionsIndex]['maxBid']        = "Aucune mise";
                $bannerAuctions[$auctionsIndex]['maxBidderName'] = "Aucun(e)";
            }

            $dateNowBanner                              = $objDate->dateNow();
            $timeLeftBanner                            = $objDate->dateDiff($dateNowBanner, $bannerAuction["dateFinish"]);
            $bannerAuctions[$auctionsIndex]['timeLeft'] = $timeLeftBanner;

            $stampImages = $objFileToUpload->selectAllById($bannerAuction['stamp_id'], "stamp_id");

            foreach ($stampImages as $stampImagesIndex => $image) {
                if ($image["position"] == 0) {
                    $bannerAuctions[$auctionsIndex]['fileName']        = $image['file'];
                    $bannerAuctions[$auctionsIndex]['fileDescription'] = $image['description'];
                }
            }
        }
        $errors = $validator->getErrors();
        return View::render('auction/show', ['errors' => $errors, "images" => $stampImageInfo, "auction" => $auction, "stampInfo" => $stampInfo, "bannerAuctions" => $bannerAuctions]);
            }

        } else {
            return view::redirect('login');
        }
    }

    public function storeLord($data = [])
    {

        $validator = new Validator;
        $validator->field('bid', $data['bid'], "Le champ enchère")->number();

        if (! empty($_SESSION)) {
            $objAuction      = new Auction;
            $objStamp        = new Stamp;
            $objFileToUpload = new FileToUpload;
            $obtCondition    = new Conditions;
            $objBid          = new Bid;
            $objUser         = new User;
            $objDate         = new Date;

            if ($validator->isSuccess()) {

                $auctionJustBid = $objAuction->selectId($data["id"]);

                $maxBid = $objBid->selectWhereIdMax("auction_id", $data["id"], "bid");

                if ($data["bid"] > $auctionJustBid["startPrice"] && $data["bid"] > $maxBid["bid"]) {
                    $dataInsert               = [];
                    $dataInsert["bid"]        = $data["bid"];
                    $dataInsert["auction_id"] = $data["id"];
                    $dataInsert["user_id"]    = $_SESSION['user_id'];

                    $insertBid = $objBid->insert($dataInsert);
                    if ($insertBid) {
                        return view::redirect('auction/lord');
                    } else {
                        return View::render('error', ['msg' => "Impossible de saisir l'enchère"]);
                    }

                } else {
                    return View::render('error', ['msg' => "Votre enchère est trop petite."]);
                }

            } else {

                $auctions   = $objAuction->selectAllById(1, "lordLike");

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

                    $dateNow                              = $objDate->dateNow();
                    $timeLeft                             = $objDate->dateDiff($dateNow, $auction["dateFinish"]);
                    $auctions[$auctionsIndex]['timeLeft'] = $timeLeft;

                    $stampImages = $objFileToUpload->selectAllById($auction['stamp_id'], "stamp_id");

                    foreach ($stampImages as $stampImagesIndex => $image) {
                        if ($image["position"] == 0) {
                            $auctions[$auctionsIndex]['fileName']        = $image['file'];
                            $auctions[$auctionsIndex]['fileDescription'] = $image['description'];
                        }
                    }
                }

                $errors = $validator->getErrors();
                return View::render('auction/catalogueLord', ['errors' => $errors, 'bidData' => $data, 'auctions' => $auctions]);
            }

        } else {
            return view::redirect('login');
        }
    }
}
