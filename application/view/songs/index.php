<div class="container">
    <h1>Songs</h1>
    <h2>You are in the View: application/view/song/index.php (everything in this box comes from that file)</h2>
    <!-- add song form -->
    <div class="box">
        <h3>Add a song</h3>
        <form action="<?php echo URL; ?>songs/addsong" method="POST">
            <label>Artist</label>
            <input type="text" name="artist" value="" required />
            <label>Track</label>
            <input type="text" name="track" value="" required />
            <label>Link</label>
            <input type="text" name="link" value="" />
            <input type="submit" name="submit_add_song" value="Submit" />
        </form>
        <h3>Add a song (Using Ajax with Query String)</h3>
        <form>
            <label>Artist</label>
            <input type="text" name="artist" id="artist" value="" required />
            <label>Track</label>
            <input type="text" name="track" id="track" value="" required />
            <label>Link</label>
            <input type="text" name="link" id="link" value="" />
            <button type="button" name="submit_add_song_button" id="submit_add_song_button">Submit</button>
        </form>
    </div>
    <!-- main content output -->
    <div class="box">
        <h3>Amount of songs: <?php echo $amount_of_songs; ?></h3>
        <h3>Amount of songs (via AJAX)</h3>
        <div id="javascript-ajax-result-box"></div>
        <div>
            <button id="javascript-ajax-button">Click here to get the amount of songs via Ajax (will be displayed in #javascript-ajax-result-box ABOVE)</button>
        </div>
        <h3>List of songs (data from model)</h3>
        <table>
            <thead style="background-color: #ddd; font-weight: bold;">
            <tr>
                <td>Id</td>
                <td>Artist</td>
                <td>Track</td>
                <td>Link</td>
                <td>DELETE</td>
                <td>EDIT</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($songs as $song) { ?>
                <tr>
                    <td><?php if (isset($song->id)) echo htmlspecialchars($song->id, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php if (isset($song->artist)) echo htmlspecialchars($song->artist, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php if (isset($song->track)) echo htmlspecialchars($song->track, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if (isset($song->link)) { ?>
                            <a href="<?php echo htmlspecialchars($song->link, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($song->link, ENT_QUOTES, 'UTF-8'); ?></a>
                        <?php } ?>
                    </td>
                    <td><a href="<?php echo URL . 'songs/deletesong/' . htmlspecialchars($song->id, ENT_QUOTES, 'UTF-8'); ?>">delete</a></td>
                    <td><a href="<?php echo URL . 'songs/editsong/' . htmlspecialchars($song->id, ENT_QUOTES, 'UTF-8'); ?>">edit</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
