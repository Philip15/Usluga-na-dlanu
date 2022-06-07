import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.testng.Assert;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.AfterSuite;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.BeforeSuite;
import org.testng.annotations.Optional;
import org.testng.annotations.Parameters;
import org.testng.annotations.Test;

public class OdobravanjeRegistracijeTestovi 
{   
    private static WebDriver driver;
    private static String browserType;

    @BeforeSuite
    @Parameters("browser")
    public void beforeSuite(@Optional("chrome") String browser)
    {
	browserType = browser;
	Common.setupDriver(browser);
    }

    @BeforeMethod
    public void beforeMethod()
    {
        driver = Common.initializeDriver(browserType);
        String customSQL = "INSERT INTO `korisnici` (`idKorisnika`, `korisnickoIme`, `lozinka`, `email`, `ime`, `prezime`, `profilnaSlika`, `opis`, `pruzalac`, `adresa`, `lat`, `lon`, `idKategorije`, `administrator`) VALUES ('99', 'tasha', '$2y$10$eEmIhXlzMtjuPX9k4BsfkO1dlNKDc9kT2fQP8c.5a2i5YUcownR.i', 'tasha@tasha.tasha', 'tasha', 'tasha', NULL, NULL, '2', 'asd', '42', '42', '1', '0');";
	Common.resetDatabase(driver,customSQL);
	driver = Common.disposeDriver(driver);
	driver = Common.initializeDriver(browserType);
        navigate(driver);
    }

    @Test
    public void NemaZahteva()
    {
        driver = Common.disposeDriver(driver);
	driver = Common.initializeDriver(browserType);
        String customSQL = "INSERT INTO `korisnici` (`idKorisnika`, `korisnickoIme`, `lozinka`, `email`, `ime`, `prezime`, `profilnaSlika`, `opis`, `pruzalac`, `adresa`, `lat`, `lon`, `idKategorije`, `administrator`) VALUES ('99', 'tasha', '$$2y$10$eEmIhXlzMtjuPX9k4BsfkO1dlNKDc9kT2fQP8c.5a2i5YUcownR.i', 'tasha@tasha.tasha', 'tasha', 'tasha', NULL, NULL, '0', 'asd', '42', '42', '1', '0');";
	Common.resetDatabase(driver,customSQL);
	driver = Common.disposeDriver(driver);
	driver = Common.initializeDriver(browserType);
        navigate(driver);
        Assert.assertEquals(
            driver.findElement(By.cssSelector("body>div")).getText(),
            "Ne postoje zahtevi koje treba odobriti!");
    }
    
    @Test
    public void PrihvatanjeZahteva()
    {
        Assert.assertEquals(
            driver.findElement(By.cssSelector(".row:nth-child(1) > .mb-2")).getText(),
            "tasha");
        driver.findElement(By.linkText("Prihvati")).click();
        Assert.assertTrue(driver.findElements(By.cssSelector(".row:nth-child(1) > .mb-2")).isEmpty());
        driver.navigate().to(Common.baseURL+"/profile?id=99");
        Assert.assertEquals(
            driver.findElement(By.className("fs-4")).getText(),
            "tasha tasha");
    }
    
    @Test
    public void OdbijanjeZahteva()
    {
        Assert.assertEquals(
            driver.findElement(By.cssSelector(".row:nth-child(1) > .mb-2")).getText(),
            "tasha");
        driver.findElement(By.linkText("Odbij")).click();
        Assert.assertTrue(driver.findElements(By.cssSelector(".row:nth-child(1) > .mb-2")).isEmpty());
        driver.navigate().to(Common.baseURL+"/profile?id=99");
        Assert.assertEquals(
            driver.findElement(By.cssSelector("h1")).getText(),
            "404 - File Not Found");
    }
    
    @AfterMethod
    public void afterMethod()
    {
        Common.logout(driver);
	driver = Common.disposeDriver(driver);
    }

    @AfterSuite
    public void afterSuite()
    {
	
    }

    public void navigate(WebDriver driver)
    {
        Common.login(driver, "lazar", "qwerty");
        driver.findElement(By.cssSelector(".rounded-circle:nth-child(1)")).click();
        driver.findElement(By.cssSelector("body > header > nav > div > ul > li:nth-child(5) > a")).click();
        Assert.assertEquals(
	    driver.getCurrentUrl(),
	    Common.baseURL+"/AdminController/accountRequests");
	System.out.println("Navigated to accountRequests");
    }
}
