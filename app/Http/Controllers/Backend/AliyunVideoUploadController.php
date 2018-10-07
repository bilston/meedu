<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use vod\Request\V20170321 as vod;
use App\Http\Controllers\Controller;

class AliyunVideoUploadController extends Controller
{
    public function createVideoToken(Request $request)
    {
        $title = $request->input('title');
        $filename = $request->input('filename');
        $profile = \DefaultProfile::getProfile(
            config('meedu.upload.video.aliyun.region', ''),
            config('meedu.upload.video.aliyun.access_key_id', ''),
            config('meedu.upload.video.aliyun.access_key_secret', '')
        );
        $client = new \DefaultAcsClient($profile);
        $request = new vod\CreateUploadVideoRequest();
        $request->setTitle($title);
        $request->setFileName($filename);
        $response = $client->getAcsResponse($request);

        return [
            'upload_auth' => $response->UploadAuth,
            'upload_address' => $response->UploadAddress,
            'video_id' => $response->VideoId,
            'request_id' => $response->RequestId,
        ];
    }

    public function refreshVideoToken(Request $request)
    {
        $videoId = $request->input('video_id');
        $profile = \DefaultProfile::getProfile(
            config('meedu.upload.video.aliyun.region', ''),
            config('meedu.upload.video.aliyun.access_key_id', ''),
            config('meedu.upload.video.aliyun.access_key_secret', '')
        );
        $client = new \DefaultAcsClient($profile);
        $request = new vod\RefreshUploadVideoRequest();
        $request->setVideoId($videoId);
        $response = $client->getAcsResponse($request);

        return [
            'upload_auth' => $response->UploadAuth,
            'upload_address' => $response->UploadAddress,
            'video_id' => $response->VideoId,
            'request_id' => $response->RequestId,
        ];
    }
}