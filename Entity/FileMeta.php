<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\MediaManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Phlexible\Bundle\MediaSiteBundle\Entity\File;
use Phlexible\Bundle\MetaSetBundle\Entity\MetaSetField;

/**
 * File meta
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @ORM\Entity
 * @ORM\Table(name="media_file_meta")
 */
class FileMeta
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36, options={"fixed"=true})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="set_id", type="string", length=36, options={"fixed"=true})
     */
    private $setId;

    /**
     * @var string
     * @ORM\Column(name="language", type="string", length=2, options={"fixed"=true})
     */
    private $language;

    /**
     * @var string
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var MetaSetField
     * @ORM\OneToOne(targetEntity="Phlexible\Bundle\MetaSetBundle\Entity\MetaSetField")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    private $field;

    /**
     * @var File
     * @ORM\ManyToOne(targetEntity="Phlexible\Bundle\MediaSiteBundle\Entity\File")
     * @ORM\JoinColumns(
     *   @ORM\JoinColumn(name="file_id", referencedColumnName="id"),
     *   @ORM\JoinColumn(name="file_version", referencedColumnName="version")
     * )
     */
    private $file;
}