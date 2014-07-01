template "/home/#{node[:temp][:bashrc][:user]}/.bashrc" do
  source "bashrc.erb"
  owner node[:temp][:bashrc][:user]
  group node[:temp][:bashrc][:user]
  variables({
    :paths => node[:temp][:bashrc][:paths],
    :envs => node[:temp][:bashrc][:envs]
  })
end