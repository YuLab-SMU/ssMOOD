<template>
    <transition name="modal">
        <div v-if="visible" class="webr-modal-overlay" @click.self="close">
            <div class="webr-modal webr-modal-limited">
                <header class="webr-modal-header">
                     <h2>{{ title }}</h2>
                    <button class="webr-modal-close" @click="close"></button>
                </header>
                <section class="webr-modal-body">
                    
                    <div class="Rcontainer">
                        <pre><p id="out">{{ message }}</p></pre>
                        <div class="Rrow">
                            <div class="text">
                                <div ref="webraceEditor" style="height: 400px; width: 800px;"></div>
                                
                            </div>
                            <div class="image">
                                <canvas id="plot-canvas" width="400px" height="400px"></canvas>
                            </div>
                        </div>
                        <button :disabled="enbutton" @click="executeCode" class="execute-button">{{ $t('hv15') }}</button>
                         <TerminalDisplay :output="terminalOutput" />
                    </div>
                </section>
            </div>
        </div>
    </transition>
</template>
<script>
import ace from 'ace-builds';
import 'ace-builds/src-noconflict/mode-r';
import 'ace-builds/src-noconflict/theme-monokai';
import {WebR} from 'webr';
import TerminalDisplay from './TerminalDisplay.vue';

export
default {
    name: 'WebRWindows',
      components: {
    // 在 components 对象中注册 TerminalDisplay 组件
    TerminalDisplay
  },
    data() {
        return {
            newwebR: null,
            editor: null,
            code: '',
            terminalOutput: "",
            message: "Loading webR, please wait...",
            enbutton: true
        };
    },
    props: {
        value: {
            type: Boolean,
            required: true
        },
        title: {
            type: String,
            default: 'WebR Windows'
        },
        detail: {
            type: String,
            default: ''
        },
        code1: {
            type: String,
            default: ''
        },
        data1: {
            type: String,
            default: ''
        }
    },
    async mounted() {
        // 创建WebR实例
        this.newwebR = new WebR();
        await this.runCodeWithRetries();
        console.log('Code updated:1');
        this.message = 'WebR is loaded';
        this.enbutton = false;
        this.message = 'Loading library, please wait...';
        await this.newwebR.installPackages(['jsonlite'], true);
        this.message = 'ok';


    },
    watch: {
        visible(newVal) {
            if (newVal) {
                this.$nextTick(() => {
                    this.code = this.code1;
                    this.editor = ace.edit(this.$refs.webraceEditor);
                    this.editor.session.setMode('ace/mode/r');
                    this.editor.setTheme('ace/theme/monokai');
                    this.editor.setValue(this.code);
                    this.editor.clearSelection();
                    this.editor.session.on('change', () => {
                        this.code = this.editor.getValue();
                        console.log('Code updated:', this.code);
                    });
                });
            }
        },
    },

    computed: {
        visible() {
            return this.value;
        },
    },
    methods: {
        async runCodeWithRetries() {
            let success = false;
            do {
                try {
                    const result = await this.newwebR.evalR('print("hello world")');
                    this.output = result;
                    success = true;
                } catch (error) {
                    console.error('loading', error);
                    await this.sleep(1000);
                }
            }while(success == true)
        },
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        close() {
            this.$emit('input', false);
        },
        async executeCode() {
            const canvas = document.getElementById('plot-canvas');
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
      
            try {
                await this.newwebR.evalRVoid(`webr::canvas() 
library(jsonlite)
jsondata <- '${this.data1}'
data <- fromJSON(jsondata)
${this.code}
dev.off()`);
                const msgs = await this.newwebR.flush();
                msgs.forEach(msg => {
                    if (msg.type === 'canvas' && msg.data.event === 'canvasImage') {
                        const canvas = document.getElementById('plot-canvas');
                        //canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                        canvas.getContext('2d').drawImage(msg.data.image, 0, 0, canvas.width, canvas.height);
                    } else {
                        console.log(msg);
                        if (msg.type === 'stdout') {
                            this.terminalOutput += msg.data + "\n";
                        } else {
                            this.terminalOutput += msg + "\n";
                        }
                    }
                });
            } catch (error) {
                console.error('Error executing code:', error);
            }
        }
    }
};
</script>
<style scoped>
.webr-modal-overlay {
    position: fixed; /* 确保覆盖整个视口 */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.webr-modal {
    width: 100%; /* 占据整个宽度 */
    height: 100%; /* 占据整个高度 */
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1001;
}

.webr-modal-limited {
    width: 100%; /* 占据整个宽度 */
    height: 100%; /* 减去 header 的高度 */
    overflow: auto;
}
.webr-modal-header {
    display: flex; /* 使用 flex 布局 */
    justify-content: space-between; /* 子元素两端对齐 */
    align-items: center; /* 子元素垂直居中 */
    padding: 1em; /* 添加一些内边距以便更好的视觉效果 */
    background-color: white; /* 确保背景颜色 */
    color: black; /* 确保文本颜色 */
    z-index: 1002;
    height: 60px; 
    padding: 120px 60px 0 20px;/*内边距padding: top right bottom left;*/
}

.webr-modal-header h2 {
    margin: 0;
    font-size: 1.5em;
}
.webr-modal-close {
    margin-right: 50px;
    background-color: rgb(93, 116, 162);
    color: white;
    font-size: 1.2em;
    transition: background-color 0.2s, transform 0.2s;
    border: none;
    border-radius: 50%; /* 圆形按钮 */
    width: 40px; /* 固定宽度 */
    height: 40px; /* 固定高度 */
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* 添加阴影 */
    cursor: pointer; /* 鼠标悬停时显示手型指针 */
    display: flex; /* 使按钮居中 */
}

.webr-modal-close:hover {
    background-color: rgba(93, 116, 162, 0.7);
    transform: scale(1.1); /* 鼠标悬停时放大 */
}

.webr-modal-close::before {
    content: 'X'; 
    font-size: 1.2em;

}

.webr-modal-body {
    padding-top: 120px;
    display: flex;
    /* 确保是flex布局 */
    flex-direction: row;
    /* 水平排列 */
    align-items: center;
    /* 垂直居中对齐 */
}
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.5s;
}
.modal-enter, .modal-leave-to {
    opacity: 0;
}
.Rcontainer {
  display: flex;
  flex-direction: column; /* 子元素垂直排列 */
  justify-content: center; /* 水平居中 */
  align-items: center; /* 垂直居中 */
}

.Rrow {
  display: flex;
  gap: 20px; 
  justify-content: center; /* 水平居中 */
  align-items: center; /* 垂直居中 */
  width: 100%; /* 行宽度为容器宽度 */
}

.Rrow .text {
  max-width: 800px; /* 限制文字宽度 */
  word-wrap: break-word; /* 自动换行 */
}

.Rrow .image {
  margin-right: 20px; /* 图片与文字之间的间距 */
}
        
.execute-button {
    padding: 10px 20px;
    margin-top: 10px;
    background-color: rgb(093, 116, 162);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    /* 鼠标悬停时显示手型指针 */
    outline: none;
    /* 移除焦点时的轮廓 */
}
.execute-button:hover {
    background-color: rgba(093, 116, 162, 0.8);
    /* 鼠标悬停时的背景颜色 */
}
.ace_editor {
    border: 1px solid #ccc;
    border-radius: 5px;
}
#plot-canvas {
    border: 2px solid rgb(093, 116, 162);
    border-radius: 10px; /* 设置边框圆角的半径 */
}
</style>