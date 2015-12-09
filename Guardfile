guard 'phpunit', :tests_path => 'src', :cli => '-c app/ --colors' do
  watch(%r{^.+Test\.php$}) # Watch all your tests
  watch(%r{(.+/.+)Bundle/(.+)\.php$}) { |m| "#{m[1]}Bundle/Tests/#{m[2]}Test.php" } # Watch all files in your bundles and run the respective tests on change
end
