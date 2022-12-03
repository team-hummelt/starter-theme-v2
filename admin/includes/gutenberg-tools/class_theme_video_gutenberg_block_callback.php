<?php

class Theme_Video_Gutenberg_Block_Callback
{
    public static function callback_video_block_type($attributes)
    {
        ob_start();
        $json = new stdClass();
        $poster = '';
        $videoUrl = '';
        $defaultImg = 'default-image.jpg';
        $idTypes = ['youtube', 'vimeo'];
        isset($attributes['selectedSourceType']) && $attributes['selectedSourceType'] ? $extern = $attributes['selectedSourceType'] : $extern = '';
        isset($attributes['selectedExternSourceType']) && $attributes['selectedExternSourceType'] ? $selectedExternSourceType = $attributes['selectedExternSourceType'] : $selectedExternSourceType = '';
        isset($attributes['externVideoUrl']) && $attributes['externVideoUrl'] ? $externVideoUrl = $attributes['externVideoUrl'] : $externVideoUrl = '';
        isset($attributes['externVideoId']) && $attributes['externVideoId'] ? $externVideoId = $attributes['externVideoId'] : $externVideoId = '';
        isset($attributes['externesCoverImgAktiv']) && $attributes['externesCoverImgAktiv'] ? $externesCoverImgAktiv = $attributes['externesCoverImgAktiv'] : $externesCoverImgAktiv = 0;
        isset($attributes['postId']) && $attributes['postId'] ? $postId = $attributes['postId'] : $postId = '';
        isset($attributes['mediaData']) && $attributes['mediaData'] ? $mediaData = $attributes['mediaData'] : $mediaData = '';
        isset($attributes['mediaVideoData']) && $attributes['mediaVideoData'] ? $mediaVideoData = $attributes['mediaVideoData'] : $mediaVideoData = '';
        isset($attributes['selectedCategory']) && $attributes['selectedCategory'] ? $selectedCategory = $attributes['selectedCategory'] : $selectedCategory = '';
        isset($attributes['videoTitelAktiv']) ? $videoTitelAktiv = 0 : $videoTitelAktiv = 1;
        isset($attributes['customVideoTitel']) && $attributes['customVideoTitel'] ? $customVideoTitel = $attributes['customVideoTitel'] : $customVideoTitel = '';
        isset($attributes['className']) && $attributes['className'] ? $className = ' ' . $attributes['className'] : $className = '';

        //Image Data
        if ($mediaData) {
            $mediaData = json_decode($mediaData, true);
        }

        isset($mediaData['id']) ? $mediaImgId = $mediaData['id'] : $mediaImgId = '';
        isset($mediaData['url']) ? $mediaImgUrl = $mediaData['url'] : $mediaImgUrl = '';
        isset($mediaData['width']) ? $mediaImgWidth = $mediaData['width'] : $mediaImgWidth = '';
        isset($mediaData['height']) ? $mediaImgHeight = $mediaData['height'] : $mediaImgHeight = '';
        isset($mediaData['mimeType']) ? $mediaImgMimeType = $mediaData['mimeType'] : $mediaImgMimeType = '';

        //Video Data
        if ($mediaVideoData) {
            $mediaVideoData = json_decode($mediaVideoData, true);
        }

        isset($mediaVideoData['id']) ? $mediaVideoId = $mediaVideoData['id'] : $mediaVideoId = '';
        isset($mediaVideoData['url']) ? $mediaUrl = $mediaVideoData['url'] : $mediaUrl = '';
        isset($mediaVideoData['width']) ? $mediaWidth = $mediaVideoData['width'] : $mediaWidth = '';
        isset($mediaVideoData['height']) ? $mediaHeight = $mediaVideoData['height'] : $mediaHeight = '';
        isset($mediaVideoData['title']) ? $mediaTitle = $mediaVideoData['title'] : $mediaTitle = '';
        isset($mediaVideoData['fileLength']) ? $mediaFileLength = $mediaVideoData['fileLength'] : $mediaFileLength = '';
        isset($mediaVideoData['mimeType']) ? $mediaMimeType = $mediaVideoData['mimeType'] : $mediaMimeType = '';
        isset($mediaVideoData['mediaIcon']) ? $mediaIcon = $mediaVideoData['mediaIcon'] : $mediaIcon = '';

        if ($videoTitelAktiv) {
            $title = $mediaTitle;
        } else {
            $title = $customVideoTitel;
        }

        if($selectedExternSourceType === 'vimeo'){
            $externType = 'vimeo';
        } elseif ($selectedExternSourceType === 'youtube') {
            $externType = 'youtube';
        } else {
            $externType = 'link';
        }

        if (in_array($selectedExternSourceType, $idTypes) && !$externVideoId) {
            return ob_get_clean();
        }
        $poster = $mediaImgUrl;
        if ($extern == 'extern') {
            $type = 'text/html';

            if (!$selectedExternSourceType && !$externVideoUrl) {
                return ob_get_clean();
            }

            if (!$externesCoverImgAktiv) {
                if (!$mediaImgUrl) {
                    $url = $defaultImg;
                } else {
                    $url = $mediaImgUrl;
                }
                $poster = $url;
            }

            if (!$selectedExternSourceType) {
                $formats = ['mp4', 'webm', 'ogg', 'quicktime', 'mpeg', 'x-msvideo', 'x-sgi-movie', '3gpp'];
                $exType = substr($externVideoUrl, strrpos($externVideoUrl, '.') + 1);
                if (in_array($exType, $formats)) {
                    $type = "video/$exType";
                } else {
                    return ob_get_clean();
                }
                $videoUrl = $externVideoUrl;
            }

            switch ($selectedExternSourceType) {
                case 'youtube':
                case 'vimeo':
                    $videoUrl = '';
                    $type = 'text/html';
                    break;
            }

        } else {
            if(!$mediaUrl){
                return ob_get_clean();
            }
            $poster = $defaultImg;
            if($mediaImgUrl) {
                $poster = $mediaImgUrl;
            }
            $externType = '';
            $videoUrl = $mediaUrl;
            $type = $mediaMimeType;
        }

        $target = self::getHupaGenerateRandomId(6, 0,6);
        $response = "<div class=\"theme-video-$target theme-video-data$className\"
                 data-target=\"$target\"
                 data-title=\"$title\"
                 data-extern-id=\"$externVideoId\"
                 data-type=\"$type\"
                 data-poster=\"$poster\"
                 data-href=\"$videoUrl\"
                 data-extern-poster=\"$externesCoverImgAktiv\"
                 data-extern-type=\"$externType\">
            </div>";
        echo preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'', $response));
        return ob_get_clean();
    }


    /**
     * @param int $passwordlength
     * @param int $numNonAlpha
     * @param int $numNumberChars
     * @param bool $useCapitalLetter
     * @return string
     */
    private static function getHupaGenerateRandomId(int $passwordlength = 12, int $numNonAlpha = 1, int $numNumberChars = 4, bool $useCapitalLetter = true): string
    {
        $numberChars = '123456789';
        //$specialChars = '!$&?*-:.,+@_';
        $specialChars = '!$%&=?*-;.,+~@_';
        $secureChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $stack = $secureChars;
        if ($useCapitalLetter) {
            $stack .= strtoupper($secureChars);
        }
        $count = $passwordlength - $numNonAlpha - $numNumberChars;
        $temp = str_shuffle($stack);
        $stack = substr($temp, 0, $count);
        if ($numNonAlpha > 0) {
            $temp = str_shuffle($specialChars);
            $stack .= substr($temp, 0, $numNonAlpha);
        }
        if ($numNumberChars > 0) {
            $temp = str_shuffle($numberChars);
            $stack .= substr($temp, 0, $numNumberChars);
        }

        return str_shuffle($stack);
    }


}