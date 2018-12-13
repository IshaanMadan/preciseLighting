<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Helper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class FileUploader
 * @package Yosto\OrderAttachment\Helper
 */
class FileUploader
{
    protected $_mediaDirectory;

    protected $_fileUploaderFactory;

    /**
     * FileUploader constructor.
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     */
    function __construct(Filesystem $filesystem, UploaderFactory $uploaderFactory)
    {
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $uploaderFactory;
    }

    /**
     * Save file to directory
     *
     * @param string $path
     * @param $quoteId
     * @param $fileId
     * @return bool|null
     */
    public function uploadFile($path = "order_attachment", $quoteId, $fileId) {
        $target = $this->_mediaDirectory->getAbsolutePath($path .'/' . $quoteId . '/');
        try {
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            try {
                $uploader = $this->_fileUploaderFactory->create(
                    ['fileId' => $fileId]
                );
            }catch (\Exception $e) {
                return null;
            }

            $uploader->setAllowedExtensions(['pdf', 'docx', 'csv', 'xsl']);
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($target);
            return $result['file'];
        } catch (\Exception $e) {
            return false;
        }
    }

}