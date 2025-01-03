#!/bin/bash

# 获取当前所在分支
get_current_branch() {
  current_branch=$(git symbolic-ref --short HEAD)
  echo "当前分支: $current_branch"
}

# 创建新分支
create_new_branch() {
  read -p "请输入新分支的版本号（如 v1.0.0）： " branch_name
  git checkout -b "$branch_name"
  echo "已创建并切换到新分支：$branch_name"
}

# 提交更改
commit_changes() {
  read -p "请输入提交信息： " commit_message
  git add .
  git commit -m "$commit_message"
  echo "已提交更改: $commit_message"
}

# 合并到 master 分支
merge_to_master() {
  git checkout master  # 或 main 分支，如果主分支名是 main
  git pull origin master  # 拉取最新的 master 代码
  read -p "请输入要合并的分支名称： " branch_name
  git merge "$branch_name"
  git push origin master  # 将合并后的代码推送到远程
  echo "已将 $branch_name 合并到 master 并推送到远程仓库"
}

# 显示当前分支
show_current_branch() {
  get_current_branch
}

# 显示菜单
show_menu() {
  echo "===================================="
  echo "Git 操作菜单"
  echo "1. 创建新分支"
  echo "2. 提交更改"
  echo "3. 合并分支到 master"
  echo "4. 显示当前分支"
  echo "5. 退出"
  echo "===================================="
}

# 主程序
while true; do
  show_menu
  read -p "请输入选择（1-5）: " choice
  case $choice in
    1)
      create_new_branch
      ;;
    2)
      commit_changes
      ;;
    3)
      merge_to_master
      ;;
    4)
      show_current_branch
      ;;
    5)
      echo "退出脚本"
      break
      ;;
    *)
      echo "无效选择，请重新选择！"
      ;;
  esac
done
