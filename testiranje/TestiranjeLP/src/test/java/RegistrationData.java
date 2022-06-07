public class RegistrationData 
{
    public String email;
    public String firstName;
    public String lastName;
    public String username;
    public String password;
    public String reenterpassword;
    public boolean conditions;
    public String errMsg;
    public String data;

    public RegistrationData(String data)
    {
	this.data=data;
	String[] splits=data.split("\\|");
	email=splits[0];
	firstName=splits[1];
	lastName=splits[2];
	username=splits[3];
	password=splits[4];
	reenterpassword=splits[5];
	conditions=Boolean.parseBoolean(splits[6]);
	errMsg=splits[7];
    }
}
