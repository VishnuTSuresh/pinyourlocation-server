<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PinnedLocation extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
/*
INSERT INTO `pinned_locations` 
(`id`, `date`, `location`, `description`, `user_id`, `created_at`, `updated_at`) 
VALUES 
(NULL, '2016-07-01', 'office', '', '1', '2016-07-01 00:00:00', '2016-07-01 00:00:00'), 
(NULL, '2016-07-04', 'leave', '', '1', '2016-07-04 00:00:00', '2016-07-04 00:00:00'),
(NULL, '2016-07-05', 'office', '', '1', '2016-07-04 00:00:00', '2016-07-04 00:00:00'),
(NULL, '2016-07-06', 'office', '', '1', '2016-07-04 00:00:00', '2016-07-04 00:00:00'),
(NULL, '2016-07-07', 'home', '', '1', '2016-07-04 00:00:00', '2016-07-04 00:00:00'),
(NULL, '2016-07-08', 'office', '', '1', '2016-07-04 00:00:00', '2016-07-04 00:00:00');
*/