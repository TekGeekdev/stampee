<?php
namespace App\Models;

use App\Models\CRUD;

class Auction extends CRUD
{
    protected $table      = 'auction';
    protected $primaryKey = 'id';
    protected $fillable   = ['name', 'dateStart', 'dateFinish', 'startPrice', 'lordLike', 'state_id', 'stamp_id'];

    public function filter($filters)
    {
        $params = [];
        $sql    = "SELECT auction.id, auction.name,
					auction.dateStart,
					auction.dateFinish,
                    auction.startPrice,
                    auction.lordLike,
                    auction.state_id,
                    auction.stamp_id,
                    stamp.name AS nameStamp,
                    stamp.dateRelease,
                    stamp.draw,
                    stamp.width,
                    stamp.height,
                    stamp.certified,
                    stamp.color_id,
                    stamp.user_id,
                    stamp.countryOrigin_id,
                    stamp.conditions_id,
                    stamp.content
                    FROM auction INNER JOIN stamp ON auction.stamp_id = stamp.id WHERE true";

        if (! empty($filters["conditions_id"])) {
            $sql .= " AND conditions_id = :conditions_id";
            $params["conditions_id"] = $filters["conditions_id"];
        }

        if (! empty($filters["countryOrigin_id"])) {
            $sql .= " AND countryOrigin_id = :countryOrigin_id";
            $params["countryOrigin_id"] = $filters["countryOrigin_id"];
        }

        if (! empty($filters["color_id"])) {
            $sql .= " AND color_id = :color_id";
            $params["color_id"] = $filters["color_id"];
        }

        if (! empty($filters["minimum"])) {
            $sql .= " AND startPrice >= :minimum";
            $params["minimum"] = $filters["minimum"];
        }

        if (! empty($filters["maximum"])) {
            $sql .= " AND startPrice <= :maximum";
            $params["maximum"] = $filters["maximum"];
        }

        if (! empty($filters["minimumDate"])) {
            $sql .= " AND dateRelease >= :minimumDate";
            $params["minimumDate"] = $filters["minimumDate"];
        }

        if (! empty($filters["maximumDate"])) {
            $sql .= " AND dateRelease <= :maximumDate";
            $params["maximumDate"] = $filters["maximumDate"];
        }

        // echo($sql);
        $stmt = parent::prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function filterLord($filters)
    {
        $params = [];
        $sql    = "SELECT auction.id, auction.name,
					auction.dateStart,
					auction.dateFinish,
                    auction.startPrice,
                    auction.lordLike,
                    auction.state_id,
                    auction.stamp_id,
                    stamp.name AS nameStamp,
                    stamp.dateRelease,
                    stamp.draw,
                    stamp.width,
                    stamp.height,
                    stamp.certified,
                    stamp.color_id,
                    stamp.user_id,
                    stamp.countryOrigin_id,
                    stamp.conditions_id,
                    stamp.content
                    FROM auction INNER JOIN stamp ON auction.stamp_id = stamp.id WHERE lordLike = 1";

        if (! empty($filters["conditions_id"])) {
            $sql .= " AND conditions_id = :conditions_id";
            $params["conditions_id"] = $filters["conditions_id"];
        }

        if (! empty($filters["countryOrigin_id"])) {
            $sql .= " AND countryOrigin_id = :countryOrigin_id";
            $params["countryOrigin_id"] = $filters["countryOrigin_id"];
        }

        if (! empty($filters["color_id"])) {
            $sql .= " AND color_id = :color_id";
            $params["color_id"] = $filters["color_id"];
        }

        if (! empty($filters["minimum"])) {
            $sql .= " AND startPrice >= :minimum";
            $params["minimum"] = $filters["minimum"];
        }

        if (! empty($filters["maximum"])) {
            $sql .= " AND startPrice <= :maximum";
            $params["maximum"] = $filters["maximum"];
        }

        if (! empty($filters["minimumDate"])) {
            $sql .= " AND dateRelease >= :minimumDate";
            $params["minimumDate"] = $filters["minimumDate"];
        }

        if (! empty($filters["maximumDate"])) {
            $sql .= " AND dateRelease <= :maximumDate";
            $params["maximumDate"] = $filters["maximumDate"];
        }

        // echo($sql);
        $stmt = parent::prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
