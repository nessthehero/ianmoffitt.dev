ls -la

git status

git fetch origin

git clean -xdf

git checkout ${{ github.head_ref }}.${{ github.sha }}
