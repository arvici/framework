<?php
/**
 * SampleEntity.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SampleEntity
 * @package App\Entity
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
