module.exports = function (grunt) {
	require("load-grunt-tasks")(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),

		makepot: {
			options: {
				exclude: ["node_modules/.*"],
				domainPath: "/languages",
				type: "wp-plugin",
				potHeaders: {
					"report-msgid-bugs-to":
						"https://github.com/eric-mathison/register-and-login/issues",
					poedit: true,
					"x-poedit-keywordslist": true,
				},
			},
			files: {
				src: ["**/*.php", "src/*.js"],
			},
		},

		addtextdomain: {
			options: {
				textdomain: "register-and-login",
				updateDomains: true,
			},
			php: {
				files: {
					src: ["**/*.php", "src/*.js", "!node_modules/**/*.php"],
				},
			},
		},

		wpcss: {
			options: {
				commentSpacing: true,
				config: "alphabetical",
			},
			files: {
				src: "includes/css/register-and-login.css",
				dest: "includes/css/register-and-login.css",
			},
		},

		postcss: {
			options: {
				map: true,
				processors: [require("autoprefixer")()],
			},
			dist: {
				src: "includes/css/register-and-login.css",
				dest: "includes/css/register-and-login.css",
			},
		},
	});

	grunt.registerTask("build", ["addtextdomain", "makepot", "wpcss", "postcss"]);
};
