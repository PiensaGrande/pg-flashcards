<?php namespace pg_flashcards; ?>
<?php

	if(!isset($_SESSION)) { session_start(); }
	
	function message() {
		if (isset($_SESSION["pgfc_message"])) {
			$output = "<div class=\"message\">";
			$output .= htmlentities($_SESSION["pgfc_message"]);
			$output .= "</div>";
			
			// clear message after use
			$_SESSION["pgfc_message"] = null;
			
			return $output;
		}
	}

	function errors() {
		if (isset($_SESSION["pgfc_errors"])) {
			$errors = $_SESSION["pgfc_errors"];
			
			// clear message after use
			$_SESSION["pgfc_errors"] = null;
			
			return $errors;
		}
	}
	
?>
