function lazypush() {
    git add .
    git commit -a -m "$1"
    git push origin HEAD:master
}

function lazypull(){
	git pull origin master

}
