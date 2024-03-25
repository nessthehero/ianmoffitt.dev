require('dotenv').config();
const EleventyFetch = require("@11ty/eleventy-fetch");

async function getPosts() {
    const postSource = process.env.POSTS;

    if (postSource !== '' && postSource !== null && typeof postSource !== 'undefined') {
		let json = await EleventyFetch(postSource, {
			duration: "15m", // 15 mins
			type: "json" // also supports "text" or "buffer"
		});

        return json;
    } else {
        return {
            "data": []
        };
    }
}

module.exports = async function () {
    return getPosts();
}