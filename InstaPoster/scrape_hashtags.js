const puppeteer = require("puppeteer");

async function scrapeHashtags(keyword){
    let browserData = {
        // headless: false,
        // devtools: true,
        // defaultViewport: null,
        slowmo: 50,
    }
    const browser = await puppeteer.launch(browserData);
    const page = await browser.newPage();
    await page.goto("https://www.all-hashtag.com/", {waitUntil: "networkidle0"});
    await page.waitFor("#keyword");
    await page.type("#keyword", keyword);
    await page.click(".btn-gen");
    await page.waitFor("#copy-hashtags");
    let hashtags = await page.evaluate(() => {
        return $("#copy-hashtags").text();
    });
    console.log(hashtags);
    browser.close();
    process.exit(0);
}

var myArgs = process.argv.slice(2);
scrapeHashtags(myArgs[0]);