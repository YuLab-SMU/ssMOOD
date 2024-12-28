import subprocess
import re

# 获取当前所在分支
def get_current_branch():
    result = subprocess.run(['git', 'symbolic-ref', '--short', 'HEAD'], capture_output=True, text=True)
    current_branch = result.stdout.strip()
    print(f"当前分支: {current_branch}")

# 创建新分支
def create_new_branch():
    branch_name = input("请输入新分支的版本号（如 v1.0.0）： ")
    subprocess.run(['git', 'checkout', '-b', branch_name])
    print(f"已创建并切换到新分支：{branch_name}")

# 提交更改
# 检查提交信息的格式是否符合规范（例如：'feat: 新增功能'）
def is_commit_message_valid(commit_message):
    pattern = re.compile(r"^(feat|fix|docs|style|refactor|perf|test)\(.*\): .+$")
    return bool(pattern.match(commit_message))

# 可用的提交类型
commit_types = [
    "feat",   # 新增功能
    "fix",    # 修复 bug
    "docs",   # 文档修改
    "style",  # 代码格式（不影响功能的修改）
    "refactor",  # 代码重构
    "perf",   # 性能优化
    "test"    # 测试
]

# 提交更改
def commit_changes():
    # 显示提交类型供用户选择
    print("选择提交类型：")
    for i, commit_type in enumerate(commit_types, 1):
        print(f"{i}. {commit_type}")

    # 获取用户选择的提交类型
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

    # 获取范围信息（可选）
    scope = input("请输入范围（如有，例：user, api），不输入则留空： ").strip()

    # 获取提交描述
    description = input("请输入提交描述： ")

    # 生成提交信息
    if scope:
        commit_message = f"{commit_type}({scope}): {description}"
    else:
        commit_message = f"{commit_type}: {description}"

    # 打印提交信息
    print(f"提交信息: {commit_message}")

    # 自动化添加文件
    subprocess.run(['git', 'add', '.'])

    # 提交更改
    subprocess.run(['git', 'commit', '-m', commit_message])

    # 提交成功提示
    print(f"已提交更改: {commit_message}")

    # 提交后是否继续提交其他更改
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
# 合并到 master 分支
def merge_to_master():
    subprocess.run(['git', 'checkout', 'master'])
    subprocess.run(['git', 'pull', 'origin', 'master'])
    branch_name = input("请输入要合并的分支名称： ")
    subprocess.run(['git', 'merge', branch_name])
    subprocess.run(['git', 'push', 'origin', 'master'])
    print(f"已将 {branch_name} 合并到 master 并推送到远程仓库")

# 列出所有分支并切换分支
def list_and_switch_branch():
    # 列出所有本地分支
    result = subprocess.run(['git', 'branch'], capture_output=True, text=True)
    branches = result.stdout.strip().split('\n')
    
    print("所有本地分支：")
    for i, branch in enumerate(branches, 1):
        print(f"{i}. {branch.strip()}")

    # 获取用户选择的分支
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

# 显示菜单
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

# 主程序
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
