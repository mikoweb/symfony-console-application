<?php

namespace App\Module\Song\Infrastructure\Dataset;

/**
 * @link https://www.kaggle.com/datasets/undefinenull/million-song-dataset-spotify-lastfm
 */
final class SongDatasetLocationConstant
{
    public const FOLDER_NAME = 'song_dataset';
    public const FILE_NAME = 'song_dataset.zip';
    public const DOWNLOAD_URL = 'https://cdn-141.anonfiles.com/OfJ32cqaz9/a57c9208-1688838547/song_dataset.zip';
    public const MUSIC_INFO_FILE_NAME = 'Music Info.csv';
    public const USER_LISTENING_HISTORY_FILE_NAME = 'User Listening History.csv';
}
