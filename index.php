<?php

# Used in scripts to block direct call 
define('ANTI_DIRECT', 'Content here doesn't matter.');

# Installation settings (for business user)
require 'settings.php';

# Installation settings (for developer)
require 'settings_developer.php';

# Pre-processing for output (as a rule html content)
require 'php' . DIRECTORY_SEPARATOR . 'index_before_output.php';

# Output (at the moment — html only)
require 'php' . DIRECTORY_SEPARATOR . 'index_output.php';

?>