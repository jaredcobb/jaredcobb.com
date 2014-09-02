##Grunt / Zurb Foundation

This project uses some great tools to build the resources such as:

- Node
- NPM
- Ruby
- Bower
- Bundler
- Zurb Foundation
- Grunt

####Initial Installation

- Make sure Node (`brew install node`), Ruby (`brew install ruby`), Bundler (`gem install bundler`), Grunt (`npm install -g grunt-cli`), Bower (`npm install -g bower`), and the Zurb Foundation CLI gem (`gem install foundation`) are installed.
- Run `bundle install` to install the proper version of Sass and other dependencies from within this subfolder.
- Run `npm install` to install all the proper npm modules for the Grunt build
- New projects should run `foundation new _foundation` from within this subfolder. This will clone a new copy of Zurb Foundation (latest production version) into a subfolder named '\_foundation'.
- Foundation provides two settings files: `./_foundation/bower_components/foundation/scss/foundation.scss` which imports specific foundation modules that you might want to customize in your project, and `./_foundation/bower_components/foundation/scss/foundation/_settings.scss` which is an official Foundation settings file that exposes variables for you to customize many of the Foundation styles.
- Make a copy of the above two files and place them into `./sass/foundation/`. (**Rename** `foundation.scss` to `_foundation.scss` after you copy it so that the file itself doesn't get built as its own .css file by Sass).
- Build the project with `grunt` or `grunt production`.

####Updating Zurb Foundation Later

- Execute `foundation update` from within `./_foundation/`
- When you upgrade, do a diff between the stock settings at `./_foundation/bower_components/foundation/scss/foundation/_settings.scss` and the copy (and customized) settings at `./sass/foundation/_settings.scss`
- If Foundation added and new modules since the last upgrade, you might also want to compare the `foundation.scss` library include file as well.

####Other Notes

- In `style.scss` I'm really just using this file as a main library include file. The final output will create a built .css file called `style.css`.
