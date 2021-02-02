<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Video Entity
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $url
 * @property int|null $bitrate
 * @property string|null $duration
 * @property int|null $size
 * @property string|null $download_link
 * @property string|null $website
 */
class Video extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'url' => true,
        'bitrate' => true,
        'duration' => true,
        'size' => true,
        'download_link' => true,
        'website' => true,
    ];
}
