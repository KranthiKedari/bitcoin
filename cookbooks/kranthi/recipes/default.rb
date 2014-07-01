node[:temp][:mysql][:databases].each do |database|
  # Create database
  execute "add-mysql-db-#{database}" do
    command "/usr/bin/mysql -u root -p#{node[:mysql][:server_root_password]} -e \"" +
        "CREATE DATABASE #{database};\" " +
        "mysql"
    action :run
    ignore_failure true
  end
end

node[:temp][:mysql][:users].each do |user|
  # Create user
  execute "add-mysql-user-#{user[:username]}" do
    command "/usr/bin/mysql -u root -p#{node[:mysql][:server_root_password]} -D mysql -r -B -N -e \"CREATE USER #{user[:username]}\""
    action :run
    ignore_failure true
  end

  execute "grant-mysql-perms-#{user[:username]}" do
    command "/usr/bin/mysql -u root -p#{node[:mysql][:server_root_password]} -D mysql -r -B -N -e \"GRANT ALL on *.* to #{user[:username]}\""
    action :run
    ignore_failure true
  end
end


# Remove the 000-default site
apache_site "000-default" do
  enable false
end


node[:temp][:web_apps].each do |app|
  

  web_app app[:server_name] do
    if web_app app[:template]
      template web_app app[:template]
    end
    server_name app[:server_name]
    server_aliases app[:server_aliases]
    docroot app[:docroot]
    app_environment app[:app_environment]
    app_platform app[:app_platform]
    aliases app[:aliases]
  end
end