<?php

// ----------------------------------------------------------------------
// eFiction 3.2
// Copyright (c) 2007 by Tammy Keefer
// Valid HTML 4.01 Transitional
// Based on eFiction 1.1
// Copyright (C) 2003 by Rebecca Smallwood.
// http://efiction.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

if(!defined("_CHARSET")) exit( );


$output .= "<div id=\"pagetitle\">"._SUBMITTED."</div>";
$view = isset($_GET['view']) ? $_GET['view'] : false;

//edited chapters
$result = dbquery("SELECT story.title as storytitle, chapter.uid, chapter.sid, story.catid, chapter.wordcount, chapter.chapid, chapter.inorder, chapter.title, chapter.submittime, chapter.edited, "._PENNAMEFIELD." as penname, ratings.rating as rating FROM (".TABLEPREFIX."fanfiction_chapters as chapter, "._AUTHORTABLE.") LEFT JOIN ".TABLEPREFIX."fanfiction_stories as story ON story.sid = chapter.sid LEFT JOIN ".TABLEPREFIX."fanfiction_ratings as ratings on ratings.rid = story.rid WHERE chapter.validated = '0' AND (chapter.edited = 1 OR chapter.edited = 2) AND chapter.uid = "._UIDFIELD." ORDER BY submittime");

if(dbnumrows($result)) {
	$output .="<h2>Edits</h2>";
	$output .= "<table class=\"tblborder\" cellspacing=\"0\" cellpadding=\"0\" style=\"margin: 0 auto; width: 90%; border = .5px solid black\"><tr class=\"tblborder\"><th style = \"border: .5px solid black;\">"._EDIT."</th><th style = \"border: .5px solid black;\">"._TITLE."</th><th style = \"border: .5px solid black;\">"._WORDCOUNT."</th><th style = \"border: .5px solid black;\">".Rating."</th><th style = \"border: .5px solid black;\">".Submitted."</th><th style = \"border: .5px solid black;\">"._AUTHOR."</th><th style = \"border: .5px solid black;\">"._CATEGORY."</th><th style = \"border: .5px solid black;\">"._OPTIONS."</th><th style = \"border: .5px solid black;\">".Validator."</tr>";
	$array = explode(",", $admincats);
	while ($story = dbassoc($result)) {
		if(!$admincats || $_GET['view'] == "all" || sizeof(array_intersect(explode(",", $story['catid']), explode(",", $admincats)))) {
			$output .= "<tr class=\"tblborder\"style = \"border: .5px solid black;\">";
			if($story['edited'] == 2){$friendly = "Rejected";}
			else $friendly = "Edit";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$friendly."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\"><a href=\"viewstory.php?sid=$story[sid]\">".stripslashes($story['storytitle'])."</a>";
			if(isset($story['title'])) $output .= " <b>:</b> <a href=\"viewstory.php?sid=$story[sid]&amp;chapter=$story[inorder]\">".stripslashes($story['title'])."</a>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['rating']."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['wordcount']."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['submittime']."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\"><a href=\"viewuser.php?uid=$story[uid]\">$story[penname]</a></td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".catlist($story['catid'])."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\"><a href=\"admin.php?action=validate&amp;chapid=$story[chapid]\">"._VALIDATE."</a><br />"._DELETE.": <a href=\"stories.php?action=delete&amp;chapid=$story[chapid]&amp;sid=$story[sid]&amp;admin=1&amp;uid=$story[uid]\">"._CHAPTER."</a> "._OR." <a href=\"stories.php?action=delete&amp;sid=$story[sid]&amp;admin=1\">"._STORY."</a><br /></td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['Validator']."</td>";
		}
	}
	$output .= "</table>";
}
else $output .= write_message(_NORESULTS);
//new chapters
$output .= 	"<p style=\"text-align: right; margin: 1em;\"><a href=\"admin.php?action=submitted&amp;view=".($view == "all" ? "cats\">"._VIEWMYCATS : "all\">"._VIEWALL)."</a></p>";
$result = dbquery("SELECT story.title as storytitle, chapter.uid, chapter.sid, story.catid, chapter.wordcount, chapter.chapid, chapter.inorder, chapter.title, chapter.submittime, chapter.edited, Validators.penname as Validator, "._PENNAMEFIELD." as penname,  ratings.rating as rating FROM (".TABLEPREFIX."fanfiction_chapters as chapter, "._AUTHORTABLE.") LEFT JOIN ".TABLEPREFIX."fanfiction_stories as story ON story.sid = chapter.sid LEFT JOIN fanfiction_authors as Validators on Validators.uid = chapter.v_uid LEFT JOIN ".TABLEPREFIX."fanfiction_ratings as ratings on ratings.rid = story.rid WHERE chapter.validated = '0' AND (chapter.edited = 0 OR chapter.edited = 3) AND chapter.uid = "._UIDFIELD." ORDER BY submittime");

if(dbnumrows($result)) {
	$output .="<h2>New Submission</h2>";
	$output .= "<table class=\"tblborder\" cellspacing=\"0\" cellpadding=\"0\" style=\"margin: 0 auto; width: 90%; border: .5px solid black;\"><tr class=\"tblborder\"><th style = \"border: .5px solid black;\">"._EDIT."</th><th style = \"border: .5px solid black;\">"._TITLE."</th><th style = \"border: .5px solid black;\">"._WORDCOUNT."</th><th style = \"border: .5px solid black;\">".Rating."</th><th style = \"border: .5px solid black;\">".Submitted."</th><th style = \"border: .5px solid black;\">"._AUTHOR."</th><th style = \"border: .5px solid black;\">"._CATEGORY."</th><th style = \"border: .5px solid black;\">"._OPTIONS."</th><th style = \"border: .5px solid black;\">".Validator."</th></tr>";
$array = explode(",", $admincats);
	while ($story = dbassoc($result)) {
		if(!$admincats || $_GET['view'] == "all" || sizeof(array_intersect(explode(",", $story['catid']), explode(",", $admincats)))) {
			$output .= "<tr class=\"tblborder\">";
			if($story['edited'] == 3){$friendly = "Rejected";}
			else $friendly = "New";
			$output .= "<td class=\"tblborder\" style = \"border: .5px solid black;\">".$friendly."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\"><a href=\"viewstory.php?sid=$story[sid]\">".stripslashes($story['storytitle'])."</a>";
			if(isset($story['title'])) $output .= " <b>:</b> <a href=\"viewstory.php?sid=$story[sid]&amp;chapter=$story[inorder]\">".stripslashes($story['title'])."</a>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['rating']."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['wordcount']."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['submittime']."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\"><a href=\"viewuser.php?uid=$story[uid]\">$story[penname]</a></td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".catlist($story['catid'])."</td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\"><a href=\"admin.php?action=validate&amp;sid=$story[sid]&amp;chapid=$story[chapid]\">"._VALIDATE."</a><br />"._DELETE.": <a href=\"stories.php?action=delete&amp;chapid=$story[chapid]&amp;sid=$story[sid]&amp;admin=1&amp;uid=$story[uid]\">"._CHAPTER."</a> "._OR." <a href=\"stories.php?action=delete&amp;sid=$story[sid]&amp;admin=1\">"._STORY."</a><br /></td>";
			$output .= "<td class=\"tblborder\"style = \"border: .5px solid black;\">".$story['Validator']."</td>";
		}
	}
	$output .= "</table>";
}
else $output .= write_message(_NORESULTS);

?>