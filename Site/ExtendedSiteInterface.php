<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\MediaManagerBundle\Site;

use Phlexible\Bundle\MediaSiteBundle\Site\SiteInterface;
use Phlexible\Bundle\MediaSiteBundle\Model\FileInterface;
use Phlexible\Bundle\MediaSiteBundle\Model\FolderInterface;

/**
 * Media site interface
 * Represents a complete set of classes used to get a virtual set of folders and files
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface ExtendedSiteInterface extends SiteInterface
{
    /**
     * @param ExtendedFolderInterface $folder
     * @param array                   $metasets
     * @param string                  $userId
     *
     * @return ExtendedFolderInterface
     */
    public function setFolderMetasets(ExtendedFolderInterface $folder, array $metasets, $userId);

    /**
     * @param ExtendedFileInterface $file
     * @param array                 $metasets
     * @param string                $userId
     *
     * @return ExtendedFileInterface
     */
    public function setFileMetasets(ExtendedFileInterface $file, array $metasets, $userId);

    /**
     * @param ExtendedFileInterface $file
     * @param string                $assetType
     * @param string                $userId
     *
     * @return ExtendedFileInterface
     */
    public function setFileAssetType(ExtendedFileInterface $file, $assetType, $userId);

    /**
     * @param ExtendedFileInterface $file
     * @param string                $documenttype
     * @param string                $userId
     *
     * @return ExtendedFileInterface
     */
    public function setFileDocumenttype(ExtendedFileInterface $file, $documenttype, $userId);
}