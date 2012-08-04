<?php
/**
 * HtmlEmail.php
 *
 * Copyright (c) 2012 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of BBA NBMC.
 *
 * BBA NBMC is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BBA NBMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BBA NBMC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Model
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * HtmlEmail Model.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Model
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Model_HtmlEmail extends Zend_Mail
{
    public function buildHtml()
    {
        // Important, without this line the example don't work!
        // The images will be attached to the email but these will be not
        // showed inline
        $this->setType(Zend_Mime::MULTIPART_RELATED);

        $matches = array();

        preg_match_all("#file://([^'\"]+)#i",
                       $this->getBodyHtml(true),
                       $matches);
        $matches = array_unique($matches[1]);

        if (count($matches ) > 0) {
            foreach ($matches as $key => $filename) {
                if (is_readable($filename)) {
                    $at = $this->createAttachment(file_get_contents($filename));
                    $at->type = $this->mimeByExtension($filename);
                    $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                    $at->encoding = Zend_Mime::ENCODING_BASE64;
                    $at->id = 'cid_' . md5_file($filename);
                    $this->setBodyHtml(str_replace('file://' . $filename,
                                       'cid:' . $at->id,
                                       $this->getBodyHtml(true)),
                                       'UTF-8',
                                       Zend_Mime::ENCODING_8BIT);
                }
            }
        }
    }

    public function mimeByExtension($filename)
    {
        $log = Zend_Registry::get('log');
        $log->info($filename);
        if (is_readable($filename) ) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            switch ($extension) {
                case 'gif':
                    $type = 'image/gif';
                    break;
                case 'jpg':
                case 'jpeg':
                    $type = 'image/jpg';
                    break;
                case 'png':
                    $type = 'image/png';
                    break;
                default:
                    $type = 'application/octet-stream';
            }
      }

      return $type;
  }
}