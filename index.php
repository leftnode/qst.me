<?php require_once 'html-header.php'; ?>

<p class="desc">
	qst.me is a new URL Shortening and Tracking service that allows you to enter a long URL and
	compress it to a much smaller and easier to remember URL. Additionally, if you have a large block
	of text, you can compress that to a small URL.
</p>

<h2>Very Long URL</h2>
<div class="tabs">
	<div class="spacer"></div>
	<div class="search">
		<form method="post" action="/create.php">
			<p>
				<input type="text" name="url" class="search-box" />
				<input type="submit" value="Shorten Your URL" class="button" />
			</p>
		</form>
	</div>
</div>

<div class="clearer"></div>
<h2>Document Word Counter</h2>
<div class="tabs">
	<div class="spacer"></div>
	<div class="search">
		<form method="post" action="/count.php" enctype="multipart/form-data">
			<p>
				<input type="file" name="document" size="10" />
				Min Word Size: <select name="size">
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
				</select>
				<input type="submit" value="Upload" class="button" />
			</p>
		</form>
	</div>
</div>

<div class="clearer"></div>
<h2>Block of Text</h2>
<div>
	<form method="post" action="/create.php">
		<p>
			<textarea name="block" style="width:100%;" rows="10"></textarea><br />
			<label><input type="checkbox" name="wrap" value="1" /> Wrap Text</label>&nbsp;&nbsp;&nbsp;
			<label><input type="checkbox" name="php" value="1" /> Is This PHP?</label>
			<input type="submit" value="Shorten Your Block of Text" class="button" />
		</p>
	</form>
</div>

<?php require_once 'html-footer.php';
