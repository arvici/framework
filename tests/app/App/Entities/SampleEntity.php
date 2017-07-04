<?php
/**
 * SampleEntity.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SampleEntity
 * @package App\Entities
 *
 * @ORM\Entity
 * @ORM\Table(name="sample_entity")
 */
class SampleEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
}
