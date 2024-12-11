import { src, dest, watch, series} from 'gulp';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';

const sass = gulpSass(dartSass);

export function css(done) {
    src('sass/app.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(sourcemaps.write('.'))
    .pipe(dest('css/'))
    done();
}

export function dev() {
    watch('sass/**/*.scss', css)
}

export default series(css, dev)