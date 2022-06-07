import io.github.bonigarcia.wdm.WebDriverManager;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.time.Duration;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.imageio.ImageIO;
import org.openqa.selenium.By;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.UnexpectedAlertBehaviour;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.edge.EdgeDriver;
import org.openqa.selenium.edge.EdgeOptions;
import org.testng.Assert;

public class Common 
{
    public static String baseURL="http://localhost:8080";

    public static void setupDriver(String browser)
    {
        if(browser.equals("chrome"))
        {
            WebDriverManager.chromedriver().setup();
        }
        else if (browser.equals("edge"))
        {
            WebDriverManager.edgedriver().setup();
        }
    }

    public static WebDriver initializeDriver(String browser)
    {
	WebDriver driver=null;
	if(browser.equals("chrome"))
        {
	    ChromeOptions options = new ChromeOptions();
	    //options.setUnhandledPromptBehaviour(UnexpectedAlertBehaviour.ACCEPT);
            driver = new ChromeDriver(options);
	    driver.manage().timeouts().implicitlyWait(Duration.ofSeconds(1));
        }
        else if (browser.equals("edge"))
        {
	    EdgeOptions options = new EdgeOptions();
	    options.setUnhandledPromptBehaviour(UnexpectedAlertBehaviour.ACCEPT);
	    driver = new EdgeDriver(options);
            driver.manage().timeouts().implicitlyWait(Duration.ofSeconds(1));
        }
	return driver;
    }

    public static WebDriver disposeDriver(WebDriver driver)
    {
	if(driver != null)
	{
	    driver.quit();
	}
	return null;
    }

    public static void resetDatabase(WebDriver driver,String customSQL)
    {
	driver.get("http://localhost/phpmyadmin");
	driver.manage().window().setSize(new Dimension(1265, 1000));
        try {
            Thread.sleep(1000);
        } catch (InterruptedException ex) {
            Logger.getLogger(Common.class.getName()).log(Level.SEVERE, null, ex);
        }
        driver.findElement(By.linkText("usluga_na_dlanu")).click();
        driver.findElement(By.linkText("Database: usluga_na_dlanu"));
	driver.findElement(By.linkText("Import")).click();
	driver.findElement(By.id("input_import_file"))
	    .sendKeys("D:\\Faks\\_6.Semestar\\PSI\\Usluga-na-dlanu\\docs\\Modelovanje Baze\\usluga_na_dlanu_data.sql");
	driver.findElement(By.id("buttonGo")).click();
	driver.findElement(By.className("alert")).click();
	Assert.assertEquals(
	    driver.findElement(By.cssSelector("em")).getText(), 
	    "Import has been successfully finished, 59 queries executed.");
	System.out.println("Database reset.");
        if(customSQL!=null)
        {
            driver.findElement(By.linkText("SQL")).click();
            driver.findElement(By.cssSelector(".cm-s-default textarea")).sendKeys(customSQL);
            driver.findElement(By.id("button_submit_query")).click();
            Assert.assertTrue(!driver.findElements(By.className("ic_s_success")).isEmpty());
            System.out.println("Executed custom SQL.");
        }
    }
    
    public static void login(WebDriver driver,String username,String password)
    {
        driver.manage().deleteAllCookies();
        driver.get(baseURL);
	driver.manage().window().setSize(new Dimension(1265, 1000));
        driver.findElement(By.id("loginButton")).click();
	driver.findElement(By.id("username")).sendKeys(username);
	driver.findElement(By.id("password")).sendKeys(password);
	driver.findElement(By.className("mt-4")).click();
	Assert.assertTrue(!driver.findElements(By.cssSelector(".rounded-circle:nth-child(1)")).isEmpty());
	System.out.println("Logged in as "+username);
    }
    
    public static void logout(WebDriver driver)
    {
        driver.manage().deleteAllCookies();
        System.out.println("Logged out by clearing cookies");
    }
}