def Kernel.is_windows_host?
    # Detect if we are running on Windows
    processor, platform, *rest = RUBY_PLATFORM.split("-")
    platform == 'mingw32'
end

Vagrant.configure("2") do |config|

  config.vm.box = "dev-vm-3.0-64bit"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", 2048]
  end

  config.vm.provider :lxc do |lxc, override|
    override.vm.box = "lxc-precise64"
    override.vm.box_url = "http://dl.dropbox.com/u/13510779/lxc-precise-amd64-2013-05-08.box"
  end

  config.vm.network :private_network, ip: "33.33.33.10"

  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.network :forwarded_port, guest: 443, host: 8443
  config.vm.network :forwarded_port, guest: 3306, host: 3306

  # Only mount over NFS if we're not on a windows host
  nfs = !Kernel.is_windows_host?
  config.vm.synced_folder "./", "/home/vagrant/app/", :nfs => nfs

  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path = "cookbooks"
    chef.roles_path = "roles"
    chef.add_role("project1")
  end
end
