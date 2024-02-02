const compress_images = require("compress-images");
const root = __dirname + '/..';

const INPUT_path_to_your_images = root + "/src/assets/img/**/*.{jpg,JPG,jpeg,JPEG,png,svg,gif}";
const OUTPUT_path = root + "/src/public/img/";

compress_images(INPUT_path_to_your_images, OUTPUT_path, { compress_force: false, statistic: true, autoupdate: true }, false,
	{ jpg: { engine: "mozjpeg", command: ["-quality", "60"] } },
	{ png: { engine: "pngquant", command: ["--quality=20-50", "-o"] } },
	{ svg: { engine: "svgo", command: "--multipass" } },
	{ gif: { engine: "gifsicle", command: ["--colors", "64", "--use-col=web"] } },
	function (error, completed, statistic) {
		if (typeof statistic === 'undefined') {
			console.info("[COMPRESS IMAGES] No images needing optimized. Skipping...")
		}
	}
);
