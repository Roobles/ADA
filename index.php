<?php 
	require_once('share/header-full.php');
?>
<div id="MainForm">
	<div id="HeaderControls">
		<div id="SongInfo"> 
			<h2 id="SongName"></h2>
			<span id="SongStatus"></span>
			<span id="SongPlayed"></span>
			<span id="SongLength"></span>
		</div>
		<div id="ControlNest">
			<?php PrintControlNest(); ?>
		</div>
	</div>
	<div id="TabbedContainer">
		<div id="TabbedContent">
			<?php PrintTabbedSections(); ?>
		</div>
	</div>
	<div id="VolumeControl">
		<span id="SongVolume"></span>
		<span id="SongVolumeValue"></span>
		<input id="VolumeUp" type="button" class="hidden" onclick="DownVolume(5);" value="-" />
		<input id="VolumeDown" type="button" class="hidden" onclick="UpVolume(5);" value="+" />
	</div>
	<div id="ControlButtons">
		<?php
			PrintButton("Prev", 	"playback.Prev();", 	"playback.Rewind();", 		"playback.Clear();");
			PrintButton("Pause", 	"playback.Toggle();", 	"", 				"playback.Clear();");
			PrintButton("Next", 	"playback.Next();", 	"playback.FastForward();", 	"playback.Clear();");
		?>
	</div>
	<script type="text/javascript"> $(window).load(Initialize); </script>
</div>
<?php
	require_once('share/footer.php');
?>
