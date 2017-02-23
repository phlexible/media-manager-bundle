<?php

/*
 * This file is part of the phlexible package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\MediaManagerBundle\Search;

use Phlexible\Bundle\SearchBundle\Search\SearchResult;
use Phlexible\Bundle\SearchBundle\SearchProvider\SearchProviderInterface;
use Phlexible\Bundle\UserBundle\Model\UserManagerInterface;
use Phlexible\Component\MediaManager\Meta\FileMetaDataManager;
use Phlexible\Component\MediaManager\Volume\ExtendedFileInterface;
use Phlexible\Component\Volume\VolumeManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Meta search.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class MetaSearch implements SearchProviderInterface
{
    /**
     * @var VolumeManager
     */
    private $volumeManager;

    /**
     * @var FileMetaDataManager
     */
    private $metaDataManager;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param VolumeManager                 $volumeManager
     * @param FileMetaDataManager           $metaDataManager
     * @param UserManagerInterface          $userManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        VolumeManager $volumeManager,
        FileMetaDataManager $metaDataManager,
        UserManagerInterface $userManager,
        AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->volumeManager = $volumeManager;
        $this->metaDataManager = $metaDataManager;
        $this->userManager = $userManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return 'ROLE_MEDIA';
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchKey()
    {
        return 'mm';
    }

    /**
     * {@inheritdoc}
     */
    public function search($query)
    {
        $files = [];
        foreach ($this->metaDataManager->findRawByValue($query) as $metaData) {
            $files[$metaData->getFile()->getId()] = $metaData->getFile();
        }

        $folders = [];
        $results = [];
        foreach ($files as $file) {
            /* @var $file ExtendedFileInterface */

            if (empty($folders[$file->getFolderId()])) {
                $volume = $this->volumeManager->findByFolderId($file->getFolderId());
                $folders[$file->getFolderId()] = $volume->findFolder($file->getFolderId());
            }

            if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') && !$this->authorizationChecker->isGranted('FILE_READ', $folders[$file->getFolderId()])) {
                continue;
            }

            $folderPath = $folders[$file->getFolderId()]->getIdPath();

            try {
                $createUser = $this->userManager->find($file->getCreateUserId());
            } catch (\Exception $e) {
                $createUser = $this->userManager->getSystemUser();
            }

            $results[] = new SearchResult(
                $file->getId(),
                $file->getName(),
                $createUser->getDisplayName(),
                $file->getCreatedAt(),
                '/media/'.$file->getId().'/_mm_small',
                'Mediamanager Meta Search',
                [
                    'handler' => 'media',
                    'parameters' => [
                        'start_file_id' => $file->getId(),
                        'start_folder_path' => '/'.implode('/', $folderPath),
                    ],
                ]
            );
        }

        return $results;
    }
}
