import path from 'path'
import fs from 'fs'
import { glob } from 'glob'
import { src, dest, watch, series, parallel } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'
import sharp from 'sharp'

const sass = gulpSass(dartSass)

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js'
}

export function css() { // Sin el done
    return src(paths.scss, {sourcemaps: true}) // El return es CLAVE
        .pipe( sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError) )
        .pipe( dest('public/build/css', {sourcemaps: '.'}) );
}

export function js() {
    return src(paths.js)
        .pipe(terser())
        .pipe(dest('./public/build/js'))
    }

export async function imagenes(done) {
    const srcDir = './src/img';
    const buildDir = './public/build/img';
    const images = await glob('./src/img/**/*');

    // Mapeamos a un arreglo de promesas
    const promises = images.map(file => {                                           // Calculamos la ruta relativa del archivo con respecto al directorio de origen
        const relativePath = path.relative(srcDir, path.dirname(file));             // Construimos la ruta de salida manteniendo la estructura de directorios
        const outputSubDir = path.join(buildDir, relativePath);                     // Procesamos la imagen y retornamos la promesa de sharp
        return procesarImagenes(file, outputSubDir);                                // Retorna la promesa de sharp
    });

    await Promise.all(promises);                                                    // Esperamos a que todas las promesas se resuelvan antes de finalizar la tarea
    done();
}

function procesarImagenes(file, outputSubDir) {
    if (!fs.existsSync(outputSubDir)) {
        fs.mkdirSync(outputSubDir, { recursive: true });
    }
    const baseName = path.basename(file, path.extname(file));
    const extName = path.extname(file);

    if (extName.toLowerCase() === '.svg') {
        return fs.promises.copyFile(file, path.join(outputSubDir, `${baseName}${extName}`));
    } else {
        const options = { quality: 80 };
        // Retornamos las promesas de sharp para que imagenes() pueda esperar
        return Promise.all([
            sharp(file).jpeg(options).toFile(path.join(outputSubDir, `${baseName}${extName}`)),
            sharp(file).webp(options).toFile(path.join(outputSubDir, `${baseName}.webp`)),
            sharp(file).avif().toFile(path.join(outputSubDir, `${baseName}.avif`))
        ]);
    }
}

export function dev() {
    watch( paths.scss, css );
    watch( paths.js, js );
    watch('src/img/**/*.{png,jpg}', imagenes)
}

// Exportaciones individuales (para que funcione 'gulp build', 'gulp css', etc)
export const build = parallel( js, css, imagenes, dev );

// Exportación por defecto (para que funcione solo 'gulp')
export default parallel( js, css, imagenes, dev );