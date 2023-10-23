module.exports = {
    src: {
        js: './assets/local/js/**/*.js',
        sass: './assets/local/sass/**/*.scss',
        images: './assets/local/images/**/*',
        fonts: './assets/local/fonts/**/*',
        php: './**/*.php',
    },
    dest: {
        js: './assets/prod/js/',
        css: './assets/prod/css/',
        images: './assets/prod/images',
        fonts: './assets/prod/fonts'
    },
    jsAuto: {
        pages: './assets/local/js/pages/**/*.js',
        cpts: './assets/local/js/cpts/**/*.js'
    },
    jsManual: {
		main: 'assets/local/js/main.js',
        slider: 'assets/local/js/components/slider.js',
        'interactive-map': 'assets/local/js/components/interactive-map.js',
        'quiz': 'assets/local/js/components/quiz.js',
    }
};
