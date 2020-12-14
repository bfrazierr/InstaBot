const puppeteer = require("puppeteer");
var retext = require('retext');
var pos = require('retext-pos');
var keywords = require('retext-keywords');
var toString = require('nlcst-to-string');

async function getTopMemes(count){
    let browserData = {
        // headless: false,
        // devtools: true,
        // defaultViewport: null,
        slowmo: 10
    }
    const browser = await puppeteer.launch(browserData);
    const page = await browser.newPage();
    // go to dank memes
    await page.goto("https://www.reddit.com/r/dankmemes/top/", {waitUntil: "networkidle0"});
    // inject jquery into page
    await page.evaluate(() => {
        (function() {
            function l(u, i) {
                var d = document;
                if (!d.getElementById(i)) {
                    var s = d.createElement('script');
                    s.src = u;
                    s.id = i;
                    d.body.appendChild(s);
                }
            }
            l('//code.jquery.com/jquery-3.2.1.min.js', 'jquery')
        })();
    });
    // add 5 second wait time to allow jquery to load
    await page.waitFor(5000);
    // scrape the image urls and captions
    let img_data = await page.evaluate(() => {
        let imgs = [];
        $(".scrollerItem").each(function(){
            let caption = $(this).find("._eYtD2XCVieq6emjKBH3m").text();
            let img_url = $(this).find(".ImageBox-image").attr("src");
            if(img_url != undefined){
                imgs.push({caption: caption, img_url: img_url});
            }
        });
        return imgs;
    });

    // loop through images, find keyword and add to obj
    for(i=img_data.length - 1; i>=0; i--){
        let obj = img_data[i];
        // remove any posts that do not have a jpg or jpeg extension
        let ext = get_url_extension(obj.img_url);
        if(ext != "jpg" && ext != "jpeg"){
            img_data.splice(i, 1);
            continue;
        }

        obj.keywords = [];
        retext()
            .use(pos)
            .use(keywords)
            .process(obj.caption, (err, file) => {
                if (err) throw err

                file.data.keywords.forEach(function(keyword) {
                    obj.keywords.push(toString(keyword.matches[0].node));
                })
              });
    }

    console.log(JSON.stringify(img_data));
    browser.close();
    process.exit(0);
}

function get_url_extension( url ) {
    return url.split(/[#?]/)[0].split('.').pop().trim();
}

getTopMemes();