const puppeteer = require("puppeteer");

async function loginToInsta(username, pass){
    let browserData = {
        // headless: false,
        // devtools: true,
        // defaultViewport: null,
        slowmo: 10
    }
    console.log("starting browser...");
    const browser = await puppeteer.launch(browserData);
    console.log("opening page..");
    const page = await browser.newPage();
    await page.setUserAgent("Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1");
    console.log("setting user agent...");
    console.log("navigating to instagram");
    await page.goto("http://instagram.com/", {waitUntil: "networkidle0"});
    console.log("page loaded. waiting for first login btn");
    await page.waitFor(".sqdOP.L3NKy.y3zKF");
    console.log("first login btn found.");

    // login
    // await page.screenshot({ path: 'screenshots/'+Date.now()+'.jpg', type: 'jpeg' });
    console.log("starting login");
    await page.click(".sqdOP.L3NKy.y3zKF");
    await page.type('input[name=username]', username);
    await page.type('input[name=password]', pass);
    console.log("submitting login");
    await page.click("button[type='submit']");
    // await page.screenshot({ path: 'screenshots/'+Date.now()+'.jpg', type: 'jpeg' });

    console.log("waiting for don't save btn");
    // await page.screenshot({ path: 'screenshots/'+Date.now()+'.jpg', type: 'jpeg' });
    try{
        await page.waitFor(".sqdOP.yWX7d.y3zKF");
        await page.click(".sqdOP.yWX7d.y3zKF");
        console.log("don't save clicked");
    }catch(e){
        console.log(e);
        console.log("save password prompt failed (skipping)");
    }
    // await page.screenshot({ path: 'screenshots/'+Date.now()+'.jpg', type: 'jpeg' });

    await page.waitFor("a.xWeGp");
    return {
        browser: browser,
        page: page
    };
}

async function postImage(username, password, imgSrc, caption, firstComment){
        const {browser, page} = await loginToInsta(username, password);
        // go to user page 
        await page.goto("https://instagram.com/" + username + "/");
        // await page.screenshot({ path: 'screenshots/'+Date.now()+'.jpg', type: 'jpeg' });
        let [fileChooser] = await Promise.all([
            page.waitForFileChooser(),
            page.click(".q02Nz._0TPg")
        ]);
        await fileChooser.accept([imgSrc]);
        await page.waitFor(".UP43G");
        // see if expand button exists on page
        let hasExpand = await page.evaluate(() => {
            let elems = document.querySelectorAll(".createSpriteExpand");
            return elems.length > 0;
        });

        if(hasExpand){
            await page.waitFor(".createSpriteExpand");
            await page.click(".createSpriteExpand");
        }
        
        await page.click(".UP43G");
        await page.waitFor("._472V_");
        await page.type("._472V_", caption);
        await page.click(".UP43G");
        await page.waitFor(".zGtbP.IPQK5");
        if(firstComment != ""){
            await page.goto("https://instagram.com/" + username + "/", {waitUntil: "networkidle0"});
            let post_url = await page.evaluate(() => {
                return document.querySelectorAll(".v1Nh3.kIKUG._bz0w")[0].children[0].href;
            });
            await page.goto(post_url);
            await page.waitFor("[aria-label='Comment']");
            await page.click("[aria-label='Comment']");
            await page.waitForSelector(".Ypffh", {visible: true});
            await page.type(".Ypffh", firstComment);
            await page.click(".sqdOP.yWX7d.y3zKF");
            // wait for 10 seconds to ensure comment posts
            await page.waitFor(4000);
        }
        await page.goto("https://instagram.com/" + username + "/", {waitUntil: "networkidle0"});
        await page.click(".Q46SR");
        await page.click("._34G9B.H0ovd");
        await page.waitForSelector(".aOOlW.bIiDR", {visible: true});
        await page.click(".aOOlW.bIiDR");
        browser.close();
        process.exit(0);
}


var myArgs = process.argv.slice(2);

postImage(myArgs[0], myArgs[1], myArgs[2], myArgs[3], myArgs[4]);
