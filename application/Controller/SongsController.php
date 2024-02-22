<?php

/**
 * Class SongsController
 * This is a demo Controller class.
 *
 * If you want, you can use multiple Models or Controllers.
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */

namespace Mini\Controller;

use Mini\Model\Song;

class SongsController
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/songs/index
     */
    public function index()
    {
        // Instance new Model (Song)
        $Song = new Song();
        // getting all songs and amount of songs
        $songs = $Song->getAllSongs();
        $amount_of_songs = $Song->getAmountOfSongs();

        // load views. within the views we can echo out $songs and $amount_of_songs easily
        view('_templates/header.php');
        view('songs/index.php', ["songs" => $songs]);
        view('_templates/footer.php');
    }

    /**
     * ACTION: addSong
     * This method handles what happens when you move to http://yourproject/songs/addsong
     * IMPORTANT: This is not a normal page, it's an ACTION. This is where the "add a song" form on songs/index
     * directs the user after the form submit. This method handles all the POST data from the form and then redirects
     * the user back to songs/index via the last line: header(...)
     * This is an example of how to handle a POST request.
     */
    public function addSong()
    {
        // if we have POST data to create a new song entry
        if (isset($_POST["submit_add_song"])) {
            // Instance new Model (Song)
            $Song = new Song();
            // do addSong() in model/model.php
            $Song->addSong($_POST["artist"], $_POST["track"], $_POST["link"]);
        }

        // where to go after song has been added
       redirect('songs/index');
    }

    /**
     * ACTION: deleteSong
     * This method handles what happens when you move to http://yourproject/songs/deletesong
     * IMPORTANT: This is not a normal page, it's an ACTION. This is where the "delete a song" button on songs/index
     * directs the user after the click. This method handles all the data from the GET request (in the URL!) and then
     * redirects the user back to songs/index via the last line: header(...)
     * This is an example of how to handle a GET request.
     * @param int $song_id Id of the to-delete song
     */
    public function deleteSong($song_id)
    {
        // if we have an id of a song that should be deleted
        if (isset($song_id)) {
            // Instance new Model (Song)
            $Song = new Song();
            // do deleteSong() in model/model.php
            $Song->deleteSong($song_id);
        }

        // where to go after song has been deleted
       redirect('songs/index');
    }

    /**
     * ACTION: editSong
     * This method handles what happens when you move to http://yourproject/songs/editsong
     * @param int $song_id Id of the to-edit song
     */
    public function editSong($song_id)
    {
        // if we have an id of a song that should be edited
        if (isset($song_id)) {
            // Instance new Model (Song)
            $Song = new Song();
            // do getSong() in model/model.php
            $song = $Song->getSong($song_id);

            // If the song wasn't found, then it would have returned false, and we need to display the error page
            if ($song === false) {
                $page = new \Mini\Controller\ErrorController();
                $page->index();
            } else {
                // load views. within the views we can echo out $song easily
                view('_templates/header.php');
                view('songs/edit.php', ["song" => $song]);
                view('_templates/footer.php');
            }
        } else {
            // redirect user to songs index page (as we don't have a song_id)
           redirect('songs/index');
        }
    }

    /**
     * ACTION: updateSong
     * This method handles what happens when you move to http://yourproject/songs/updatesong
     * IMPORTANT: This is not a normal page, it's an ACTION. This is where the "update a song" form on songs/edit
     * directs the user after the form submit. This method handles all the POST data from the form and then redirects
     * the user back to songs/index via the last line: header(...)
     * This is an example of how to handle a POST request.
     */
    public function updateSong()
    {
        // if we have POST data to create a new song entry
        if (isset($_POST["submit_update_song"])) {
            // Instance new Model (Song)
            $Song = new Song();
            // do updateSong() from model/model.php
            $Song->updateSong($_POST["artist"], $_POST["track"], $_POST["link"], $_POST['song_id']);
        }

        // where to go after song has been added
       redirect('songs/index');
    }

    /**
     * AJAX-ACTION: ajaxGetStats
     * TODO documentation
     */
    public function ajaxGetStats()
    {
        // Instance new Model (Song)
        $Song = new Song();
        $amount_of_songs = $Song->getAmountOfSongs();

        // simply echo out something. A supersimple API would be possible by echoing JSON here
        echo $amount_of_songs;
    }

}
