## Zurb Foundation Settings

- Foundation provides two settings files: `./_foundation/bower_components/foundation/scss/foundation.scss` which imports specific foundation modules that you might want to customize in your project, and `./_foundation/bower_components/foundation/scss/foundation/_settings.scss` which is an official Foundation settings file that exposes variables for you to customize many of the Foundation styles.
- Make a copy of the above two files and place them into `./sass/foundation/`. (**Rename** `foundation.scss` to `_foundation.scss` after you copy it so that the file itself doesn't get built as its own .css file by Sass).
