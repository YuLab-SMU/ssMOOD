import subprocess
import re

# Get the current branch
def get_current_branch():
    result = subprocess.run(['git', 'symbolic-ref', '--short', 'HEAD'], capture_output=True, text=True)
    current_branch = result.stdout.strip()
    print(f"当前分支: {current_branch}")

# Create a new branch
def create_new_branch():
    branch_name = input("请输入新分支的版本号（如 v1.0.0）： ")
    subprocess.run(['git', 'checkout', '-b', branch_name])
    print(f"已创建并切换到新分支：{branch_name}")

# Commit changes
# Check that the commit message follows the convention (e.g. 'feat: add new feature')
def is_commit_message_valid(commit_message):
    pattern = re.compile(r"^(feat|fix|docs|style|refactor|perf|test)\(.*\): .+$")
    return bool(pattern.match(commit_message))

# Available commit types
commit_types = [
    "feat",   # New feature
    "fix",    # Bug fix
    "docs",   # Documentation changes
    "style",  # Code style (changes that don't affect functionality)
    "refactor",  # Code refactoring
    "perf",   # Performance improvement
    "test"    # Tests
]

# Commit changes
def commit_changes():
    # Show commit types for the user to choose from
    print("选择提交类型：")
    for i, commit_type in enumerate(commit_types, 1):
        print(f"{i}. {commit_type}")

    # Get the commit type selected by the user
    while True:
        try:
            choice = int(input(f"请输入选择（1-{len(commit_types)}）: "))
            if 1 <= choice <= len(commit_types):
                commit_type = commit_types[choice - 1]
                break
            else:
                print(f"无效选择，请选择 1 到 {len(commit_types)} 之间的数字。")
        except ValueError:
            print(f"无效输入，请输入 1 到 {len(commit_types)} 之间的数字。")

    # Get the scope (optional)
    scope = input("请输入范围（如有，例：user, api），不输入则留空： ").strip()

    # Get the commit description
    description = input("请输入提交描述： ")

    # Build the commit message
    if scope:
        commit_message = f"{commit_type}({scope}): {description}"
    else:
        commit_message = f"{commit_type}: {description}"

    # Print the commit message
    print(f"提交信息: {commit_message}")

    # Automatically stage files
    subprocess.run(['git', 'add', '.'])

    # Commit changes
    subprocess.run(['git', 'commit', '-m', commit_message])

    # Commit success message
    print(f"已提交更改: {commit_message}")

    # Ask whether to continue committing other changes
    while True:
        continue_commit = input("是否继续提交其他更改? (y/n): ").lower()
        if continue_commit == 'y':
            commit_changes()
            break
        elif continue_commit == 'n':
            print("提交完成.")
            break
        else:
            print("无效选择，请输入 'y' 或 'n'。")
# Merge into the master branch
def merge_to_master():
    subprocess.run(['git', 'checkout', 'master'])
    subprocess.run(['git', 'pull', 'origin', 'master'])
    branch_name = input("请输入要合并的分支名称： ")
    subprocess.run(['git', 'merge', branch_name])
    subprocess.run(['git', 'push', 'origin', 'master'])
    print(f"已将 {branch_name} 合并到 master 并推送到远程仓库")

# List all branches and switch branch
def list_and_switch_branch():
    # List all local branches
    result = subprocess.run(['git', 'branch'], capture_output=True, text=True)
    branches = result.stdout.strip().split('\n')
    
    print("所有本地分支：")
    for i, branch in enumerate(branches, 1):
        print(f"{i}. {branch.strip()}")

    # Get the branch selected by the user
    while True:
        try:
            choice = int(input(f"请输入要切换的分支编号（1-{len(branches)}）： "))
            if 1 <= choice <= len(branches):
                branch_to_switch = branches[choice - 1].strip()
                branch_to_switch = branch_to_switch.replace("* ","")
                subprocess.run(['git', 'checkout', branch_to_switch])
                print(f"已切换到分支：{branch_to_switch}")
                break
            else:
                print(f"无效选择，请选择 1 到 {len(branches)} 之间的数字。")
        except ValueError:
            print(f"无效输入，请输入 1 到 {len(branches)} 之间的数字。")

# Show menu
def show_menu():
    print("====================================")
    print("Git 操作菜单")
    print("1. 创建新分支")
    print("2. 提交更改")
    print("3. 合并分支到 master")
    print("4. 显示当前分支")
    print("5. 切换分支")
    print("6. 退出")
    print("====================================")

# Main program
def main():
    while True:
        show_menu()
        choice = input("请输入选择（1-6）: ")
        if choice == '1':
            create_new_branch()
        elif choice == '2':
            commit_changes()
        elif choice == '3':
            merge_to_master()
        elif choice == '4':
            get_current_branch()
        elif choice == '5':
            list_and_switch_branch()
        elif choice == '6':
            print("退出脚本")
            break
        else:
            print("无效选择，请重新选择！")

if __name__ == "__main__":
    main()
