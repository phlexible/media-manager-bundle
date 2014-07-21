<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\MediaManagerBundle\EventListener;

use Phlexible\Bundle\DocumenttypeBundle\Model\DocumenttypeManagerInterface;
use Phlexible\Bundle\MediaSiteBundle\Event\BeforeCreateFileEvent;
use Phlexible\Bundle\MediaSiteBundle\Event\BeforeCreateFolderEvent;

/**
 * File listener
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class FileListener
{
    /**
     * @var DocumenttypeManagerInterface
     */
    private $documenttypeManager;

    /**
     * @param DocumenttypeManagerInterface $documenttypeManager
     */
    public function __construct(DocumenttypeManagerInterface $documenttypeManager)
    {
        $this->documenttypeManager = $documenttypeManager;
    }

    /**
     * @param BeforeCreateFileEvent $event
     */
    public function onBeforeCreateFile(BeforeCreateFileEvent $event)
    {
        $file = $event->getAction()->getFile();

        try {
            $documenttype = $this->documenttypeManager->findByMimetype($file->getMimeType());
        } catch (\Exception $e) {
            $documenttype = $this->documenttypeManager->find('binary');
        }

        $file
            ->setAttribute('documenttype', $documenttype->getKey())
            ->setAttribute('assettype', $documenttype->getType());
    }

    /**
     * @param BeforeCreateFolderEvent $event
     */
    public function onBeforeCreateFolder(BeforeCreateFolderEvent $event)
    {
        $folder = $event->getAction()->getFolder();

        return;
        try {
            $attributes = $folder->getAttributes();
            $attributes['metasets'][] = 'x';
            $site->setFolderAttributes($folder, $attributes);
        } catch (\Exception $e) {
        }
    }
}
