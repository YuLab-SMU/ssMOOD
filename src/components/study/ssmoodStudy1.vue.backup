<template>
  <div ref="mindMap" class="mind-map-container"></div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import * as d3 from 'd3';
import { useRouter } from 'vue-router';

const mindMapData = {
  nodes: [
    { id: '1', label: 'study1', study: 'study1', disabled: true },
    { id: '11', label: 'area', study: 'study1.area', disabled: true },
    { id: '111', label: 'DRN', study: 'study1.area.DRN', disabled: false },
    { id: '112', label: 'HIP', study: 'study1.area.HIP', disabled: false },
    { id: '113', label: 'Stri', study: 'study1.area.Stri', disabled: false },
    { id: '114', label: 'VTA', study: 'study1.area.VTA', disabled: false },
    { id: '115', label: 'mPFC', study: 'study1.area.mPFC', disabled: false },
    { id: '12', label: 'condition', study: 'study1.conditions', disabled: true },
    { id: '121', label: 'contral(0Day)', study: 'study1.conditions.C', disabled: false },
    { id: '122', label: '3Day', study: 'study1.conditions.3D', disabled: false },
    { id: '123', label: '7Day', study: 'study1.conditions.7D', disabled: false },
     { id: '124', label: '10Day', study: 'study1.conditions.10D', disabled: true },
    { id: '125', label: 'R', study: 'study1.conditions.R', disabled: false },
    { id: '126', label: 'S', study: 'study1.conditions.S', disabled: false },
  ],
  links: [
    { source: '1', target: '11' },
    { source: '11', target: '111' },
    { source: '11', target: '112' },
    { source: '11', target: '113' },
    { source: '11', target: '114' },
    { source: '11', target: '115' },
    { source: '1', target: '12' },
    { source: '12', target: '121' },
    { source: '121', target: '122' },
    { source: '122', target: '123' },
    { source: '123', target: '124' },
    { source: '124', target: '125' },
    { source: '124', target: '126' },
  ],
};

const router = useRouter();
const mindMap = ref(null);

onMounted(() => {
  const svg = d3.select(mindMap.value).append('svg')
    .attr('width', '100%')
    .attr('height', 400);

  // 设定力学模拟
  const simulation = d3.forceSimulation(mindMapData.nodes)
    .force('link', d3.forceLink(mindMapData.links).id(d => d.id).distance(100))  // 设置连线长度
    .force('charge', d3.forceManyBody().strength(-400))  // 节点之间的斥力
    .force('center', d3.forceCenter(400, 200)) // 设置整体的中心位置
    .force('x', d3.forceX(d => d.id === '1' ? 100 : 500)) // 父节点（id === '1'）固定在最左侧，其他节点向右排列
    .force('y', d3.forceY(200));  // 让所有节点都集中在纵向的中间

  // 绘制连线
  const link = svg.append('g')
    .selectAll('.link')
    .data(mindMapData.links)
    .enter().append('line')
    .attr('class', 'link')
    .style('stroke', '#ccc')
    .style('stroke-width', 2);

  // 绘制节点
  const node = svg.append('g')
    .selectAll('.node')
    .data(mindMapData.nodes)
    .enter().append('circle')
    .attr('class', 'node')
    .attr('r', 20)
    .style('fill', d => (d.disabled ? '#ccc' : '#3498db'))
    .on('click', (event, node) => { // 确保事件接收到正确的数据
      if (!node.disabled) {
        console.log('Node clicked:', node);  // 打印节点数据
        router.push({ name: 'scsDetail', params: { study: node.study } });
      }
    });

  node.append('title')
    .text(d => d.label);

  // 绘制标签
  const labels = svg.append('g')
    .selectAll('.label')
    .data(mindMapData.nodes)
    .enter().append('text')
    .attr('class', 'label')
    .attr('dy', -25)
    .attr('text-anchor', 'middle')
    .text(d => d.label);

  // 更新节点位置
  simulation.on('tick', () => {
    node.attr('cx', d => d.x)
        .attr('cy', d => d.y);

    labels.attr('x', d => d.x)
          .attr('y', d => d.y);

    link.attr('x1', d => d.source.x)
        .attr('y1', d => d.source.y)
        .attr('x2', d => d.target.x)
        .attr('y2', d => d.target.y);
  });
});
</script>

<style>
.mind-map-container {
  width: 100%;
  height: 400;
}
.mind-map-container svg {
  width: 100%;
  height: 400;
}

.node {
  cursor: pointer;
  transition: fill 0.3s ease;
}

.node:hover {
  fill: #e74c3c;
}

.link {
  stroke: #ccc;
  stroke-width: 2px;
}

.label {
  font-size: 12px;
  fill: #333;
}
</style>
